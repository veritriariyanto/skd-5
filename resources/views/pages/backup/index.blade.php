@extends('layouts.app')
@section('title', 'Manajemen Backup')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-black">Manajemen Backup Database</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 flex justify-between">
                <button type="button" onclick="showBackupModal()"
                    class="focus:outline-none text-white bg-primary hover:bg-secondary focus:ring-4 focus:ring-green-300 font-bold rounded-lg text-sm sm:text-base px-5 py-2.5">
                    Buat Backup Baru
                </button>

                <button type="button" onclick="showUploadModal()"
                    class="focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm sm:text-base px-5 py-2.5">
                    Upload File Backup
                </button>
            </div>

            <!-- Add New Backup Modal -->
            <div id="backupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Buat Backup Baru</h3>
                        <form action="{{ route('backup.create') }}" method="POST" id="createBackupForm">
                            @csrf
                            <div class="mt-2 px-7 py-3">
                                <div class="text-left space-y-3">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Password ZIP</label>
                                        <input type="password" name="zip_password" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <p class="mt-1 text-sm text-gray-500">Password ini akan digunakan untuk mengamankan
                                            file backup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Buat Backup
                                </button>
                                <button type="button" onclick="hideBackupModal()"
                                    class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Upload Modal -->
            <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Upload File Backup</h3>
                        <form action="{{ route('backup.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-2 px-7 py-3">
                                <div class="text-left space-y-3">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">File Backup (ZIP)</label>
                                        <input type="file" name="backup_file" accept=".zip" required
                                            class="mt-1 block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Password ZIP</label>
                                        <input type="password" name="zip_password" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    Upload
                                </button>
                                <button type="button" onclick="hideUploadModal()"
                                    class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($backups as $backup)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="radio" name="backup_to_restore" value="{{ $backup['filename'] }}"
                                        class="backup-radio rounded border-gray-300">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $backup['filename'] }}</td>
                                <td class="px-4 py-3">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                <td class="px-4 py-3">{{ date('Y-m-d H:i:s', $backup['created_at']) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('backup.download', $backup['filename']) }}"
                                                class="inline-flex items-center px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('backup.delete', $backup['filename']) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus backup ini?')"
                                                    class="inline-flex items-center px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada backup yang tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex space-x-4">
                <form id="restoreSelectedForm" action="{{ route('backup.restore') }}" method="POST">
                    @csrf
                    <input type="hidden" name="backup_filename" id="selected_backup_filename">
                    <input type="hidden" name="confirm_restore" id="confirm_restore" value="0">

                    <button type="button" onclick="restoreSelected()"
                        class="focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm sm:text-base px-5 py-2.5">
                        Restore Selected Backup
                    </button>
                </form>
            </div>

            <!-- Simplified Modal -->
            <div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Restore Database</h3>
                        <div class="mt-2 px-7 py-3">
                            <div class="text-left space-y-3">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Password ZIP</label>
                                    <input type="password" id="restore_zip_password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div class="flex items-center mb-4">
                                    <input type="checkbox" id="confirm_restore_checkbox" onchange="updateRestoreButton()"
                                        class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                    <label for="confirm_restore_checkbox" class="ml-2 text-sm text-gray-700">
                                        Saya yakin ingin melakukan restore database
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="confirmRestoreBtn" onclick="submitRestore()" disabled
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 disabled:opacity-50">
                                Konfirmasi Restore
                            </button>
                            <button type="button" onclick="hideRestoreModal()"
                                class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Add these new functions at the beginning of your script
                function showBackupModal() {
                    document.getElementById('backupModal').classList.remove('hidden');
                }

                function hideBackupModal() {
                    document.getElementById('backupModal').classList.add('hidden');
                    document.getElementById('createBackupForm').reset();
                }

                function showUploadModal() {
                    document.getElementById('uploadModal').classList.remove('hidden');
                }

                function hideUploadModal() {
                    document.getElementById('uploadModal').classList.add('hidden');
                }

                document.getElementById('selectAll').addEventListener('change', function() {
                    const radios = document.getElementsByClassName('backup-radio');
                    for (let radio of radios) {
                        radio.checked = this.checked;
                    }
                });

                function restoreSelected() {
                    const selectedBackup = document.querySelector('input[name="backup_to_restore"]:checked');
                    if (!selectedBackup) {
                        alert('Silakan pilih backup yang akan direstore');
                        return;
                    }

                    document.getElementById('selected_backup_filename').value = selectedBackup.value;
                    document.getElementById('restoreModal').classList.remove('hidden');
                }

                function hideRestoreModal() {
                    document.getElementById('restoreModal').classList.add('hidden');
                    document.getElementById('confirm_restore_checkbox').checked = false;
                    document.getElementById('restore_zip_password').value = '';
                    document.getElementById('confirmRestoreBtn').disabled = true;
                    document.getElementById('confirm_restore').value = '0';
                }

                // Replace existing event listeners with these new ones
                document.addEventListener('DOMContentLoaded', function() {
                    const checkbox = document.getElementById('confirm_restore_checkbox');
                    const confirmBtn = document.getElementById('confirmRestoreBtn');
                    const confirmInput = document.getElementById('confirm_restore');

                    checkbox.addEventListener('change', function() {
                        const isChecked = this.checked;
                        confirmBtn.disabled = !isChecked;
                        confirmInput.value = isChecked ? '1' : '0';
                    });

                    confirmBtn.addEventListener('click', function() {
                        const password = document.getElementById('restore_zip_password').value;
                        if (!password) {
                            alert('Silakan masukkan password ZIP');
                            return;
                        }

                        const form = document.getElementById('restoreSelectedForm');

                        // Remove any existing password input
                        const existingPassword = form.querySelector('input[name="zip_password"]');
                        if (existingPassword) {
                            existingPassword.remove();
                        }

                        // Add new password input
                        const passwordInput = document.createElement('input');
                        passwordInput.type = 'hidden';
                        passwordInput.name = 'zip_password';
                        passwordInput.value = password;
                        form.appendChild(passwordInput);

                        form.submit();
                    });
                });

                function updateRestoreButton() {
                    const checkbox = document.getElementById('confirm_restore_checkbox');
                    const confirmBtn = document.getElementById('confirmRestoreBtn');
                    const confirmInput = document.getElementById('confirm_restore');

                    console.log('Checkbox changed:', checkbox.checked); // Debug log

                    confirmBtn.disabled = !checkbox.checked;
                    confirmInput.value = checkbox.checked ? '1' : '0';
                }

                function submitRestore() {
                    const password = document.getElementById('restore_zip_password').value;
                    if (!password) {
                        alert('Silakan masukkan password ZIP');
                        return;
                    }

                    const form = document.getElementById('restoreSelectedForm');

                    // Remove any existing password input
                    const existingPassword = form.querySelector('input[name="zip_password"]');
                    if (existingPassword) {
                        existingPassword.remove();
                    }

                    // Add new password input
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'zip_password';
                    passwordInput.value = password;
                    form.appendChild(passwordInput);

                    form.submit();
                }

                function hideRestoreModal() {
                    document.getElementById('restoreModal').classList.add('hidden');
                    document.getElementById('confirm_restore_checkbox').checked = false;
                    document.getElementById('restore_zip_password').value = '';
                    document.getElementById('confirmRestoreBtn').disabled = true;
                    document.getElementById('confirm_restore').value = '0';
                }
            </script>
        @endsection
