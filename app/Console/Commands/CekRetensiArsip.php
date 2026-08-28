<?php

namespace App\Console\Commands;

use App\Models\Archive;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CekRetensiArsip extends Command
{
    protected $signature = 'retensi:cek
                            {--hari=30 : Berapa hari sebelum tanggal retensi peringatan mulai dikirim}';

    protected $description = 'Cek arsip yang mendekati atau sudah melewati tanggal retensi, lalu kirim notifikasi pengingat otomatis ke staf & admin';

    public function handle(): int
    {
        $hari = (int) $this->option('hari');
        $this->info("🔍 Mengecek arsip dengan retensi dalam {$hari} hari ke depan...");

        $totalMendekati    = $this->kirimNotifikasiMendekati($hari);
        $totalKedaluwarsa  = $this->kirimNotifikasiKedaluwarsa();

        $this->info("✅ Selesai. Notifikasi 'mendekati retensi': {$totalMendekati}, notifikasi 'kedaluwarsa': {$totalKedaluwarsa}.");
        Log::info('Cek retensi arsip dijalankan', [
            'mendekati'   => $totalMendekati,
            'kedaluwarsa' => $totalKedaluwarsa,
        ]);

        return self::SUCCESS;
    }

    /**
     * Arsip yang tanggal retensinya akan jatuh tempo dalam N hari ke depan,
     * dan belum pernah dikirimi notifikasi "mendekati".
     */
    private function kirimNotifikasiMendekati(int $hari): int
    {
        $arsipList = Archive::retensiMendekati($hari)
            ->where('retensi_notif_mendekati_terkirim', false)
            ->get();

        foreach ($arsipList as $arsip) {
            $penerima = $this->tentukanPenerima($arsip);

            foreach ($penerima as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'title'   => 'Arsip Mendekati Batas Retensi',
                    'message' => "Arsip \"{$arsip->judul}\" ({$arsip->nomor_surat}) akan mencapai batas retensi pada " .
                                 $arsip->tanggal_retensi->translatedFormat('d F Y') . ". Segera tinjau untuk dipindahkan ke inaktif atau diusulkan pemusnahan.",
                    'type'    => 'warning',
                ]);
            }

            $arsip->update(['retensi_notif_mendekati_terkirim' => true]);
            $this->line("  ⏰ {$arsip->nomor_surat} — {$arsip->judul}");
        }

        return $arsipList->count();
    }

    /**
     * Arsip yang tanggal retensinya sudah lewat dan belum pernah dikirimi
     * notifikasi "kedaluwarsa".
     */
    private function kirimNotifikasiKedaluwarsa(): int
    {
        $arsipList = Archive::retensiKedaluwarsa()
            ->where('retensi_notif_kedaluwarsa_terkirim', false)
            ->get();

        foreach ($arsipList as $arsip) {
            $penerima = $this->tentukanPenerima($arsip);
            $tindakLanjut = match ($arsip->nasib_akhir_arsip) {
                'dinilai_kembali' => 'Segera dinilai kembali sesuai Jadwal Retensi Arsip untuk menentukan nasib akhirnya.',
                default           => 'Segera tindak lanjuti (pindahkan ke gudang/inaktif atau ajukan pemusnahan).',
            };

            foreach ($penerima as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'title'   => 'Arsip Sudah Melewati Batas Retensi',
                    'message' => "Arsip \"{$arsip->judul}\" ({$arsip->nomor_surat}) sudah melewati batas retensi sejak " .
                                 $arsip->tanggal_retensi->translatedFormat('d F Y') . ". {$tindakLanjut}",
                    'type'    => 'error',
                ]);
            }

            $arsip->update(['retensi_notif_kedaluwarsa_terkirim' => true]);
            $this->line("  ⚠️ {$arsip->nomor_surat} — {$arsip->judul}");
        }

        return $arsipList->count();
    }

    /**
     * Penerima notifikasi: staf pengunggah arsip + seluruh admin.
     */
    private function tentukanPenerima(Archive $arsip): array
    {
        $ids = User::where('role', 'admin')->pluck('id')->toArray();

        if ($arsip->user_id && !in_array($arsip->user_id, $ids)) {
            $ids[] = $arsip->user_id;
        }

        return array_unique($ids);
    }
}
