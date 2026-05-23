<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Drive Service Account Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk backup file surat & arsip ke Google Drive menggunakan
    | Service Account (tidak perlu OAuth login user).
    |
    | Cara mendapatkan credentials:
    | 1. Buka https://console.cloud.google.com
    | 2. Buat project baru (atau pilih yang sudah ada)
    | 3. Aktifkan "Google Drive API" di Library
    | 4. Buat Service Account di IAM & Admin > Service Accounts
    | 5. Download JSON key dari service account tersebut
    | 6. Simpan file JSON di storage/app/google-credentials.json
    | 7. Share folder Google Drive ke email service account (dengan akses Editor)
    |
    */

    // Path ke file JSON credentials service account
    // Simpan file di: storage/app/google-credentials.json
    'credentials_path' => env(
        'GOOGLE_SERVICE_ACCOUNT_JSON',
        storage_path('app/google-credentials.json')
    ),

    // ID folder Google Drive tujuan backup
    // Cara mendapatkan: buka folder di Drive, lihat URL
    // https://drive.google.com/drive/folders/FOLDER_ID_ADA_DI_SINI
    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID', ''),

    // Nama subfolder di dalam folder tujuan (akan dibuat otomatis)
    'subfolder_arsip'     => env('GOOGLE_DRIVE_SUBFOLDER_ARSIP', 'Arsip-Surat'),
    'subfolder_disposisi' => env('GOOGLE_DRIVE_SUBFOLDER_DISPOSISI', 'Bukti-Disposisi'),

    // Batas ukuran file per upload (bytes) — default 50MB
    'max_file_size' => env('GOOGLE_DRIVE_MAX_FILE_SIZE', 52428800),

    // Timeout upload dalam detik
    'upload_timeout' => env('GOOGLE_DRIVE_UPLOAD_TIMEOUT', 120),

    // Jadwal backup otomatis (cron expression)
    // Default: setiap hari jam 01.00 dini hari
    'schedule' => env('GOOGLE_DRIVE_SCHEDULE', '0 1 * * *'),

    // Aktifkan backup otomatis terjadwal
    'auto_backup_enabled' => env('GOOGLE_DRIVE_AUTO_BACKUP', false),

];
