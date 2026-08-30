<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('jadwal_retensi_arsip')->insert([
            [
                'kode_klasifikasi'    => '000',
                'nama_klasifikasi'    => 'Surat Masuk Umum',
                'deskripsi'           => 'Surat masuk yang bersifat umum/rutin, tidak mengandung keputusan strategis.',
                'jangka_aktif_tahun'  => 2,
                'jangka_inaktif_tahun'=> 3,
                'nasib_akhir'         => 'musnah',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '001',
                'nama_klasifikasi'    => 'Surat Keluar Umum',
                'deskripsi'           => 'Surat keluar yang bersifat umum/rutin, tidak mengandung keputusan strategis.',
                'jangka_aktif_tahun'  => 2,
                'jangka_inaktif_tahun'=> 3,
                'nasib_akhir'         => 'musnah',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '100',
                'nama_klasifikasi'    => 'Surat Keputusan (SK)',
                'deskripsi'           => 'Surat Keputusan Kepala Dinas dan dokumen penetapan kebijakan.',
                'jangka_aktif_tahun'  => 5,
                'jangka_inaktif_tahun'=> 10,
                'nasib_akhir'         => 'permanen',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '200',
                'nama_klasifikasi'    => 'Dokumen Kepegawaian',
                'deskripsi'           => 'Dokumen terkait kepegawaian pegawai (SK pangkat, mutasi, cuti, dll).',
                'jangka_aktif_tahun'  => 5,
                'jangka_inaktif_tahun'=> 25,
                'nasib_akhir'         => 'permanen',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '300',
                'nama_klasifikasi'    => 'Laporan Keuangan',
                'deskripsi'           => 'Laporan realisasi anggaran, pertanggungjawaban keuangan dinas.',
                'jangka_aktif_tahun'  => 5,
                'jangka_inaktif_tahun'=> 5,
                'nasib_akhir'         => 'dinilai_kembali',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '400',
                'nama_klasifikasi'    => 'Dokumen Proyek/Kegiatan TI',
                'deskripsi'           => 'Dokumen pengadaan, pelaksanaan, dan laporan proyek teknologi informasi.',
                'jangka_aktif_tahun'  => 3,
                'jangka_inaktif_tahun'=> 5,
                'nasib_akhir'         => 'dinilai_kembali',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '500',
                'nama_klasifikasi'    => 'Notulen & Berita Acara Rapat',
                'deskripsi'           => 'Notulen rapat internal dan berita acara kegiatan rutin.',
                'jangka_aktif_tahun'  => 2,
                'jangka_inaktif_tahun'=> 3,
                'nasib_akhir'         => 'musnah',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
            [
                'kode_klasifikasi'    => '600',
                'nama_klasifikasi'    => 'Dokumen Kontrak/Perjanjian Kerja Sama',
                'deskripsi'           => 'Kontrak kerja sama dengan pihak ketiga, MoU, dan perjanjian resmi lainnya.',
                'jangka_aktif_tahun'  => 5,
                'jangka_inaktif_tahun'=> 5,
                'nasib_akhir'         => 'permanen',
                'aktif'               => true,
                'created_at'          => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('jadwal_retensi_arsip')->truncate();
    }
};
