<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    protected $backupPath;
    protected $googleDrive;

    public function __construct(GoogleDriveService $googleDrive)
    {
        $this->backupPath = storage_path('app/backups');
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
        $this->googleDrive = $googleDrive;
    }

    public function index()
    {
        $backups = collect(File::files($this->backupPath))->map(function ($file) {
            return [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'created_at' => $file->getCTime(),
            ];
        })->sortByDesc('created_at');

        return view('pages.backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'zip_password' => 'required|string|min:6'
            ]);

            $sqlFilename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s');
            $sqlFilepath = $this->backupPath . '/' . $sqlFilename . '.sql';
            $zipFilepath = $this->backupPath . '/' . $sqlFilename . '.zip';

            // Use the password from the form
            $zipPassword = $request->zip_password;

            // Get database configuration
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $database = config('database.connections.mysql.database');

            // Create temporary configuration file
            $tempConfigFile = tempnam(sys_get_temp_dir(), 'mysql');
            file_put_contents($tempConfigFile, sprintf(
                "[client]\nuser=%s\npassword=%s\nhost=%s\n",
                $username,
                $password,
                $host
            ));
            chmod($tempConfigFile, 0600);

            // Create backup command
            $command = sprintf(
                'mysqldump --defaults-extra-file=%s ' .
                    '--add-drop-table --no-tablespaces --complete-insert ' .
                    '--default-character-set=utf8mb4 %s > %s 2>&1',
                escapeshellarg($tempConfigFile),
                escapeshellarg($database),
                escapeshellarg($sqlFilepath)
            );

            // Execute backup
            $output = null;
            $returnVar = null;
            exec($command, $output, $returnVar);
            unlink($tempConfigFile);

            if ($returnVar === 0 && file_exists($sqlFilepath) && filesize($sqlFilepath) > 0) {
                // Create password-protected ZIP archive
                $zip = new \ZipArchive();
                if ($zip->open($zipFilepath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    $zip->setPassword($zipPassword);
                    $zip->addFile($sqlFilepath, basename($sqlFilepath));
                    // Set encryption method
                    $zip->setEncryptionName(basename($sqlFilepath), \ZipArchive::EM_AES_256);
                    $zip->close();

                    // Delete the SQL file after successful ZIP creation
                    unlink($sqlFilepath);

                    // Upload to Google Drive
                    try {
                        $uploadResult = $this->googleDrive->uploadFile(
                            $zipFilepath,
                            basename($zipFilepath)
                        );

                        return redirect()->route('backup.index')
                            ->with('success', 'Backup berhasil dibuat di lokal dan Google Drive.');
                    } catch (\Exception $e) {
                        return redirect()->route('backup.index')
                            ->with('warning', 'Backup berhasil dibuat di lokal tapi gagal upload ke Google Drive.');
                    }
                }
            }

            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal dibuat: ' . implode("\n", $output));
        } catch (\Exception $e) {
            if (isset($tempConfigFile) && file_exists($tempConfigFile)) {
                unlink($tempConfigFile);
            }
            if (isset($sqlFilepath) && file_exists($sqlFilepath)) {
                unlink($sqlFilepath);
            }
            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = $this->backupPath . '/' . $filename;

        if (File::exists($path)) {
            return response()->download($path);
        }

        return redirect()->route('backup.index')
            ->with('error', 'File tidak ditemukan');
    }

    public function delete($filename)
    {
        try {
            $path = $this->backupPath . '/' . $filename;

            // Delete local file
            if (File::exists($path)) {
                File::delete($path);
            }

            // Delete from Google Drive
            // Delete from Google Drive
            try {
                $this->googleDrive->deleteFile($filename);
            } catch (\Exception $e) {
                Log::warning('Failed to delete from Google Drive: ' . $e->getMessage());
            }

            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            $request->validate([
                'backup_filename' => 'required|string',
                'confirm_restore' => 'required|accepted',
                'zip_password' => 'required|string'
            ]);

            $filename = $request->backup_filename;
            $zipPath = $this->backupPath . '/' . $filename;
            $zipPassword = $request->zip_password;

            if (!File::exists($zipPath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'File backup tidak ditemukan');
            }

            // Extract SQL from password-protected ZIP
            $zip = new \ZipArchive();
            $tempSqlFile = tempnam(sys_get_temp_dir(), 'sql');

            if ($zip->open($zipPath) === TRUE) {
                $zip->setPassword($zipPassword);

                // Get the SQL file from the ZIP
                $sqlContent = $zip->getFromIndex(0);
                if ($sqlContent === false) {
                    $zip->close();
                    throw new \Exception('Password ZIP salah atau file rusak');
                }

                file_put_contents($tempSqlFile, $sqlContent);
                $zip->close();
            } else {
                throw new \Exception('Gagal membuka file backup');
            }

            // Database configuration
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $database = config('database.connections.mysql.database');

            // Create MySQL config file
            $tempConfigFile = tempnam(sys_get_temp_dir(), 'mysql');
            file_put_contents($tempConfigFile, sprintf(
                "[client]\nuser=%s\npassword=%s\nhost=%s\n",
                $username,
                $password,
                $host
            ));
            chmod($tempConfigFile, 0600);

            try {
                // Execute restore command
                $command = sprintf(
                    'mysql --defaults-extra-file=%s %s < %s',
                    escapeshellarg($tempConfigFile),
                    escapeshellarg($database),
                    escapeshellarg($tempSqlFile)
                );

                $output = [];
                $returnVar = 0;
                exec($command . " 2>&1", $output, $returnVar);

                // Cleanup
                unlink($tempConfigFile);
                unlink($tempSqlFile);

                if ($returnVar !== 0) {
                    throw new \Exception(implode("\n", $output));
                }

                return redirect()->route('backup.index')
                    ->with('success', 'Database berhasil direstore');
            } catch (\Exception $e) {
                // Cleanup on error
                if (file_exists($tempConfigFile)) unlink($tempConfigFile);
                if (file_exists($tempSqlFile)) unlink($tempSqlFile);
                throw $e;
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }

    private function generateUniqueFilename($filename)
    {
        $originalName = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $counter = 1;

        $newFilename = $filename;
        while (File::exists($this->backupPath . '/' . $newFilename)) {
            $newFilename = $originalName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $newFilename;
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:zip',
                'zip_password' => 'required|string|min:6'
            ]);

            $file = $request->file('backup_file');
            $originalFilename = $file->getClientOriginalName();

            // Generate unique filename
            $filename = $this->generateUniqueFilename($originalFilename);

            // First, verify the ZIP and password
            $zip = new \ZipArchive();
            if ($zip->open($file->getRealPath()) !== TRUE) {
                throw new \Exception('File ZIP tidak valid');
            }

            $zip->setPassword($request->zip_password);
            $firstFile = $zip->statIndex(0);

            if (!$firstFile || $zip->getFromIndex(0) === false) {
                $zip->close();
                throw new \Exception('Password ZIP salah atau file rusak');
            }
            $zip->close();

            // Ensure backup directory exists
            if (!File::exists($this->backupPath)) {
                File::makeDirectory($this->backupPath, 0755, true);
            }

            // Save to local storage using stream copy
            $destinationPath = $this->backupPath . '/' . $filename;

            // Use PHP's stream copy for reliable file handling
            if (!copy($file->getRealPath(), $destinationPath)) {
                throw new \Exception('Gagal menyimpan file ke penyimpanan lokal');
            }

            // Verify local file exists and has content
            if (!File::exists($destinationPath) || File::size($destinationPath) === 0) {
                throw new \Exception('File gagal tersimpan dengan benar di lokal');
            }

            // Only proceed with Google Drive upload if local save is confirmed
            try {
                $uploadResult = $this->googleDrive->uploadFile($destinationPath, $filename);

                // Double check local file still exists after Google Drive upload
                if (!File::exists($destinationPath)) {
                    throw new \Exception('File lokal hilang setelah upload ke Google Drive');
                }

                $message = $filename === $originalFilename
                    ? 'Backup berhasil tersimpan di lokal dan Google Drive'
                    : 'Backup berhasil tersimpan dengan nama baru: ' . $filename;

                return redirect()->route('backup.index')
                    ->with('success', $message);
            } catch (\Exception $e) {
                // Keep local file even if Google Drive upload fails
                Log::error('Google Drive upload failed: ' . $e->getMessage());
                return redirect()->route('backup.index')
                    ->with('warning', 'Backup tersimpan di lokal tapi gagal upload ke Google Drive: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Clean up any partial uploads
            if (isset($destinationPath) && File::exists($destinationPath)) {
                File::delete($destinationPath);
            }
            return redirect()->route('backup.index')
                ->with('error', 'Upload gagal: ' . $e->getMessage());
        }
    }
}
