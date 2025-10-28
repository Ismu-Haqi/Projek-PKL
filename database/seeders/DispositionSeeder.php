<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disposition;
use App\Models\User;
use App\Models\Archive;
use Carbon\Carbon;

class DispositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->get();
        $archives = Archive::all();

        if (!$admin || $staff->isEmpty() || $archives->isEmpty()) {
            $this->command->warn('⚠️ Pastikan sudah ada data User (admin & staff) dan Archive sebelum menjalankan seeder ini!');
            return;
        }

        $dispositions = [
            [
                'nomor_disposisi' => 'DISP/01/2025/0001',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Tindak Lanjut Surat Edaran COVID-19',
                'instruction' => 'Mohon untuk segera ditindaklanjuti dan disosialisasikan kepada seluruh pegawai mengenai protokol kesehatan terbaru. Buatkan laporan hasil sosialisasi paling lambat 3 hari kedepan.',
                'priority' => 'urgent',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(3),
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0002',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Verifikasi Data Aset TIK',
                'instruction' => 'Lakukan verifikasi terhadap data aset TIK yang tercantum dalam dokumen terlampir. Pastikan semua data sudah sesuai dengan kondisi fisik di lapangan.',
                'priority' => 'high',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addWeek(),
                'read_at' => Carbon::now()->subHours(5),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0003',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Pembuatan Laporan Bulanan',
                'instruction' => 'Buatkan laporan kegiatan bulanan untuk periode Desember 2024. Sertakan dokumentasi dan data pendukung yang relevan.',
                'priority' => 'normal',
                'status' => 'completed',
                'deadline' => Carbon::now()->subDays(2),
                'read_at' => Carbon::now()->subDays(8),
                'completed_at' => Carbon::now()->subDays(3),
                'notes' => 'Laporan bulanan telah selesai dibuat dan sudah dikirimkan via email. Dokumentasi lengkap tersimpan di folder laporan.',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0004',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Koordinasi Rapat Evaluasi',
                'instruction' => 'Koordinasikan jadwal rapat evaluasi dengan semua kepala bidang. Tentukan waktu dan tempat yang sesuai, kemudian informasikan kepada yang bersangkutan.',
                'priority' => 'high',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(5),
                'read_at' => Carbon::now()->subHours(2),
                'notes' => 'Sedang dalam proses koordinasi dengan kepala bidang. Estimasi selesai hari ini.',
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0005',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Update Website Resmi',
                'instruction' => 'Lakukan update konten pada website resmi sesuai dengan informasi terbaru yang telah diberikan. Pastikan semua link berfungsi dengan baik.',
                'priority' => 'normal',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(7),
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0006',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Audit Keamanan Sistem',
                'instruction' => 'Lakukan audit keamanan sistem informasi secara menyeluruh. Identifikasi potensi kerentanan dan berikan rekomendasi perbaikan.',
                'priority' => 'urgent',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(2),
                'read_at' => Carbon::now()->subDays(1),
                'notes' => 'Audit sedang berlangsung. Ditemukan beberapa isu minor yang sedang ditangani.',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0007',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Backup Data Server',
                'instruction' => 'Lakukan backup data server secara berkala sesuai jadwal. Verifikasi bahwa backup berhasil dan data dapat di-restore dengan baik.',
                'priority' => 'low',
                'status' => 'completed',
                'deadline' => Carbon::now()->subDays(5),
                'read_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(6),
                'notes' => 'Backup data server telah selesai dilakukan. Semua data terverifikasi dengan baik dan proses restore berhasil diuji.',
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0008',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Pelatihan Aplikasi Baru',
                'instruction' => 'Siapkan materi pelatihan untuk aplikasi baru yang akan diimplementasikan. Jadwalkan sesi pelatihan untuk semua pengguna.',
                'priority' => 'high',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(10),
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0009',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Pengadaan Hardware Baru',
                'instruction' => 'Buatkan proposal pengadaan hardware baru sesuai dengan kebutuhan yang telah diidentifikasi. Sertakan spesifikasi teknis dan estimasi biaya.',
                'priority' => 'normal',
                'status' => 'rejected',
                'deadline' => Carbon::now()->subDays(1),
                'read_at' => Carbon::now()->subDays(4),
                'notes' => 'Proposal ditolak karena anggaran belum tersedia untuk tahun ini. Akan diajukan kembali tahun depan.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'nomor_disposisi' => 'DISP/01/2025/0010',
                'archive_id' => $archives->random()->id,
                'from_user_id' => $admin->id,
                'to_user_id' => $staff->random()->id,
                'subject' => 'Monitoring Jaringan Internet',
                'instruction' => 'Lakukan monitoring terhadap performa jaringan internet secara real-time. Catat setiap gangguan yang terjadi dan lakukan troubleshooting.',
                'priority' => 'urgent',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(1),
                'read_at' => Carbon::now()->subMinutes(30),
                'notes' => 'Monitoring sedang berjalan. Beberapa spike terdeteksi namun sudah ditangani.',
                'created_at' => Carbon::now()->subHours(8),
            ],
        ];

        foreach ($dispositions as $data) {
            Disposition::create($data);
        }

        $this->command->info('✅ ' . count($dispositions) . ' data disposisi berhasil dibuat!');
    }
}