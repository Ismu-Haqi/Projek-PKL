<?php

namespace App\Console\Commands;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupToDrive extends Command
{
    protected $signature = 'backup:drive
                            {--type=all : Tipe backup — all, arsip, disposisi}
                            {--silent  : Tidak tampilkan output detail}';

    protected $description = 'Backup file surat & arsip ke Google Drive menggunakan Service Account';

    public function handle(GoogleDriveService $driveService): int
    {
        $type = $this->option('type');

        if (!$driveService->isConfigured()) {
            $this->error('❌ Konfigurasi Google Drive belum lengkap.');
            $this->line('   • Pastikan file credentials ada di: ' . config('google-drive.credentials_path'));
            $this->line('   • Pastikan GOOGLE_DRIVE_FOLDER_ID sudah diisi di .env');
            return self::FAILURE;
        }

        $this->info("🔄 Memulai backup Google Drive (type: {$type})...");
        $startTime = microtime(true);

        try {
            $result = match ($type) {
                'arsip'     => ['arsip' => $driveService->backupArsip(), 'disposisi' => null],
                'disposisi' => ['arsip' => null, 'disposisi' => $driveService->backupDisposisi()],
                default     => $driveService->backupAll(),
            };

            $elapsed = round(microtime(true) - $startTime, 2);

            // Tampilkan hasil
            if (!$this->option('silent')) {
                if (isset($result['arsip']) && $result['arsip']) {
                    $a = $result['arsip'];
                    $this->line("📁 Arsip   : ✅ {$a['success']} | ⏭  {$a['skipped']} | ❌ {$a['failed']} (total: {$a['total']})");
                }
                if (isset($result['disposisi']) && $result['disposisi']) {
                    $d = $result['disposisi'];
                    $this->line("📎 Disposisi: ✅ {$d['success']} | ⏭  {$d['skipped']} | ❌ {$d['failed']} (total: {$d['total']})");
                }
                if (isset($result['summary'])) {
                    $s = $result['summary'];
                    $this->newLine();
                    $this->info("✅ Selesai dalam {$elapsed}s — Total: {$s['total']} | Berhasil: {$s['success']} | Gagal: {$s['failed']}");
                }
            }

            Log::info('[BackupToDrive] Backup terjadwal selesai', [
                'type'    => $type,
                'elapsed' => $elapsed . 's',
                'result'  => $result['summary'] ?? $result,
            ]);

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Backup gagal: ' . $e->getMessage());
            Log::error('[BackupToDrive] Backup gagal: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
