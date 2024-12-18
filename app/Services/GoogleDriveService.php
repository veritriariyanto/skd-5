<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected $drive;

    public function __construct()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->refreshToken(config('services.google.refresh_token'));

        $this->drive = new Drive($client);
    }

    public function uploadFile($filePath, $fileName)
    {
        try {
            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [config('services.google.folder_id')]
            ]);

            $content = file_get_contents($filePath);
            $file = $this->drive->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return [
                'id' => $file->id,
                'link' => $file->webViewLink
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteFile($fileName)
    {
        try {
            $response = $this->drive->files->listFiles([
                'q' => "name='" . $fileName . "' and trashed=false",
                'fields' => 'files(id,name)'
            ]);

            foreach ($response->getFiles() as $file) {
                $this->drive->files->delete($file->getId());
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Google Drive deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
