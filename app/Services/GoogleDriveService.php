<?php

namespace App\Services;

use App\Models\Archive;
use App\Models\Disposition;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    protected ?\Google\Client $client = null;
    protected ?\Google\Service\Drive $driveService = null;
    protected string $rootFolderId;
    protected array $folderCache = [];

    public function __construct()
    {
        $this->rootFolderId = config('google-drive.folder_id', '');
    }

    // ============================================================
    // INISIALISASI CLIENT
    // ============================================================

    /**
     * Inisialisasi Google Client dengan OAuth 2.0 Refresh Token
     * Throw exception jika credentials tidak ditemukan / tidak valid
     */
    public function init(): static
    {
        $oauthCredPath = storage_path('app/oauth-credentials.json');
        $refreshToken  = env('GOOGLE_OAUTH_REFRESH_TOKEN');

        if (!file_exists($oauthCredPath)) {
            throw new \RuntimeException(
                "File oauth-credentials.json tidak ditemukan di storage/app/.\n" .
                "Download OAuth Client ID JSON dari Google Cloud Console dan simpan di lokasi tersebut."
            );
        }

        if (empty($refreshToken)) {
            throw new \RuntimeException(
                "GOOGLE_OAUTH_REFRESH_TOKEN belum diisi di file .env."
            );
        }

        if (empty($this->rootFolderId)) {
            throw new \RuntimeException(
                "GOOGLE_DRIVE_FOLDER_ID belum diisi di file .env."
            );
        }

        $credentials = json_decode(file_get_contents($oauthCredPath), true);
        $cred        = $credentials['installed'] ?? $credentials['web'];

        $this->client = new \Google\Client();
        $this->client->setApplicationName('GANDARIA-Backup');
        $this->client->setClientId($cred['client_id']);
        $this->client->setClientSecret($cred['client_secret']);
        $this->client->addScope(\Google\Service\Drive::DRIVE);
        $this->client->setAccessType('offline');

        // Set token dari refresh token
        $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

        $this->driveService = new \Google\Service\Drive($this->client);

        return $this;
    }

    /**
     * Cek apakah konfigurasi sudah lengkap (tanpa throw)
     */
    public function isConfigured(): bool
    {
        return file_exists(storage_path('app/oauth-credentials.json'))
            && !empty(env('GOOGLE_OAUTH_REFRESH_TOKEN'))
            && !empty(config('google-drive.folder_id'));
    }

    // ============================================================
    // FOLDER MANAGEMENT
    // ============================================================

    /**
     * Cari atau buat subfolder di dalam parent folder
     */
    public function findOrCreateFolder(string $name, string $parentId): string
    {
        $cacheKey = "{$parentId}/{$name}";
        if (isset($this->folderCache[$cacheKey])) {
            return $this->folderCache[$cacheKey];
        }

        // Cari folder yang sudah ada
        $query = "name='{$name}' and mimeType='application/vnd.google-apps.folder' " .
                 "and '{$parentId}' in parents and trashed=false";

        $result = $this->driveService->files->listFiles([
            'q'      => $query,
            'fields' => 'files(id, name)',
        ]);

        if (!empty($result->getFiles())) {
            $folderId = $result->getFiles()[0]->getId();
            $this->folderCache[$cacheKey] = $folderId;
            return $folderId;
        }

        // Buat folder baru
        $meta = new \Google\Service\Drive\DriveFile([
            'name'     => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents'  => [$parentId],
        ]);

        $folder = $this->driveService->files->create($meta, ['fields' => 'id']);
        $folderId = $folder->getId();
        $this->folderCache[$cacheKey] = $folderId;

        return $folderId;
    }

    // ============================================================
    // UPLOAD SINGLE FILE
    // ============================================================

    /**
     * Upload satu file ke folder Google Drive
     * Return: Drive file ID jika berhasil, null jika gagal
     */
    public function uploadFile(string $localPath, string $fileName, string $folderId): ?string
    {
        if (!file_exists($localPath)) {
            Log::warning("[GoogleDrive] File tidak ditemukan: {$localPath}");
            return null;
        }

        $maxSize = config('google-drive.max_file_size', 52428800);
        if (filesize($localPath) > $maxSize) {
            Log::warning("[GoogleDrive] File terlalu besar (maks " . ($maxSize / 1024 / 1024) . "MB): {$localPath}");
            return null;
        }

        try {
            $mimeType = mime_content_type($localPath) ?: 'application/octet-stream';

            $meta = new \Google\Service\Drive\DriveFile([
                'name'    => $fileName,
                'parents' => [$folderId],
            ]);

            $result = $this->driveService->files->create($meta, [
                'data'       => file_get_contents($localPath),
                'mimeType'   => $mimeType,
                'uploadType' => 'multipart',
                'fields'     => 'id, name, size',
            ]);

            return $result->getId();

        } catch (\Exception $e) {
            Log::error("[GoogleDrive] Gagal upload {$fileName}: " . $e->getMessage());
            return null;
        }
    }

    // ============================================================
    // BACKUP ARSIP (file dari tabel archives)
    // ============================================================

    /**
     * Backup semua file arsip ke Google Drive
     * Return: array statistik hasil
     */
    public function backupArsip(): array
    {
        $this->init();

        $subfolderName = config('google-drive.subfolder_arsip', 'Arsip-Surat');
        $dateFolderName = 'Backup-' . date('Y-m-d');

        // Struktur: RootFolder / Arsip-Surat / Backup-2026-05-21 /
        $arsipFolderId  = $this->findOrCreateFolder($subfolderName, $this->rootFolderId);
        $dateFolderId   = $this->findOrCreateFolder($dateFolderName, $arsipFolderId);

        $archives = Archive::whereNotNull('file_path')->get();

        $result = [
            'total'    => $archives->count(),
            'success'  => 0,
            'skipped'  => 0,
            'failed'   => 0,
            'details'  => [],
        ];

        foreach ($archives as $archive) {
            $localPath = Storage::disk('public')->path($archive->file_path);

            if (!file_exists($localPath)) {
                $result['skipped']++;
                $result['details'][] = [
                    'file'   => $archive->file_name ?? $archive->file_path,
                    'status' => 'skipped',
                    'reason' => 'File tidak ditemukan di storage',
                ];
                continue;
            }

            // Nama file di Drive: [NomorSurat]_[JudulSingkat].[ext]
            $ext       = pathinfo($archive->file_path, PATHINFO_EXTENSION);
            $safeTitle = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $archive->nomor_surat ?? 'nonum');
            $driveFileName = "{$safeTitle}_{$archive->id}.{$ext}";

            $driveId = $this->uploadFile($localPath, $driveFileName, $dateFolderId);

            if ($driveId) {
                $result['success']++;
                $result['details'][] = [
                    'file'     => $driveFileName,
                    'status'   => 'success',
                    'drive_id' => $driveId,
                ];
            } else {
                $result['failed']++;
                $result['details'][] = [
                    'file'   => $driveFileName,
                    'status' => 'failed',
                    'reason' => 'Upload gagal (lihat log)',
                ];
            }
        }

        return $result;
    }

    // ============================================================
    // BACKUP BUKTI DISPOSISI
    // ============================================================

    /**
     * Backup semua file bukti disposisi ke Google Drive
     */
    public function backupDisposisi(): array
    {
        $this->init();

        $subfolderName = config('google-drive.subfolder_disposisi', 'Bukti-Disposisi');
        $dateFolderName = 'Backup-' . date('Y-m-d');

        $disposisiFolderId = $this->findOrCreateFolder($subfolderName, $this->rootFolderId);
        $dateFolderId      = $this->findOrCreateFolder($dateFolderName, $disposisiFolderId);

        $dispositions = Disposition::whereNotNull('completion_file')->get();

        $result = [
            'total'   => $dispositions->count(),
            'success' => 0,
            'skipped' => 0,
            'failed'  => 0,
            'details' => [],
        ];

        foreach ($dispositions as $disp) {
            $localPath = Storage::disk('public')->path($disp->completion_file);

            if (!file_exists($localPath)) {
                $result['skipped']++;
                $result['details'][] = [
                    'file'   => $disp->nomor_disposisi,
                    'status' => 'skipped',
                    'reason' => 'File bukti tidak ditemukan',
                ];
                continue;
            }

            $ext           = pathinfo($disp->completion_file, PATHINFO_EXTENSION);
            $driveFileName = "{$disp->nomor_disposisi}_{$disp->id}.{$ext}";

            $driveId = $this->uploadFile($localPath, $driveFileName, $dateFolderId);

            if ($driveId) {
                $result['success']++;
                $result['details'][] = [
                    'file'     => $driveFileName,
                    'status'   => 'success',
                    'drive_id' => $driveId,
                ];
            } else {
                $result['failed']++;
                $result['details'][] = [
                    'file'   => $driveFileName,
                    'status' => 'failed',
                    'reason' => 'Upload gagal (lihat log)',
                ];
            }
        }

        return $result;
    }

    // ============================================================
    // BACKUP SEMUA (arsip + disposisi)
    // ============================================================

    public function backupAll(): array
    {
        $arsip     = $this->backupArsip();
        $disposisi = $this->backupDisposisi();

        return [
            'arsip'     => $arsip,
            'disposisi' => $disposisi,
            'summary'   => [
                'total'   => $arsip['total']   + $disposisi['total'],
                'success' => $arsip['success'] + $disposisi['success'],
                'skipped' => $arsip['skipped'] + $disposisi['skipped'],
                'failed'  => $arsip['failed']  + $disposisi['failed'],
            ],
        ];
    }

    // ============================================================
    // CEK KONEKSI
    // ============================================================

    /**
     * Tes koneksi ke Google Drive — return array status
     */
    public function testConnection(): array
    {
        try {
            $this->init();

            // Coba akses folder root
            $file = $this->driveService->files->get($this->rootFolderId, [
                'fields' => 'id, name, mimeType',
            ]);

            return [
                'success'     => true,
                'folder_name' => $file->getName(),
                'folder_id'   => $file->getId(),
                'message'     => "Koneksi berhasil! Terhubung ke folder \"{$file->getName()}\".",
            ];
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Gagal terhubung ke Google Drive: ' . $e->getMessage()];
        }
    }
}