<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada data
        if (Category::count() > 0) {
            $this->command->info('Categories already exist. Skipping...');
            return;
        }

        $categories = [
            [
                'name' => 'Surat Masuk',
                'code' => 'SM',
                'description' => 'Surat yang diterima dari pihak eksternal',
                'color' => '#007bff',
                'icon' => 'fa-inbox',
                'is_active' => true
            ],
            [
                'name' => 'Surat Keluar',
                'code' => 'SK',
                'description' => 'Surat yang dikirim ke pihak eksternal',
                'color' => '#28a745',
                'icon' => 'fa-paper-plane',
                'is_active' => true
            ],
            [
                'name' => 'Surat Keputusan',
                'code' => 'SKP',
                'description' => 'Surat keputusan resmi',
                'color' => '#dc3545',
                'icon' => 'fa-gavel',
                'is_active' => true
            ],
            [
                'name' => 'Surat Edaran',
                'code' => 'SE',
                'description' => 'Surat edaran internal/eksternal',
                'color' => '#ffc107',
                'icon' => 'fa-bullhorn',
                'is_active' => true
            ],
            [
                'name' => 'Memo Internal',
                'code' => 'MI',
                'description' => 'Memo dan komunikasi internal',
                'color' => '#6c757d',
                'icon' => 'fa-sticky-note',
                'is_active' => true
            ],
            [
                'name' => 'Nota Dinas',
                'code' => 'ND',
                'description' => 'Nota dinas antar unit',
                'color' => '#17a2b8',
                'icon' => 'fa-file-alt',
                'is_active' => true
            ],
            [
                'name' => 'Laporan',
                'code' => 'LP',
                'description' => 'Laporan kegiatan dan keuangan',
                'color' => '#6f42c1',
                'icon' => 'fa-chart-bar',
                'is_active' => true
            ],
            [
                'name' => 'Kontrak & Perjanjian',
                'code' => 'KP',
                'description' => 'Dokumen kontrak dan perjanjian kerjasama',
                'color' => '#fd7e14',
                'icon' => 'fa-handshake',
                'is_active' => true
            ],
            [
                'name' => 'Notulensi',
                'code' => 'NT',
                'description' => 'Notulensi rapat dan pertemuan',
                'color' => '#20c997',
                'icon' => 'fa-comments',
                'is_active' => true
            ],
            [
                'name' => 'Dokumen Teknis',
                'code' => 'DT',
                'description' => 'Dokumen spesifikasi dan teknis',
                'color' => '#e83e8c',
                'icon' => 'fa-cogs',
                'is_active' => true
            ],
            [
                'name' => 'Proposal',
                'code' => 'PR',
                'description' => 'Proposal kegiatan dan anggaran',
                'color' => '#795548',
                'icon' => 'fa-file-contract',
                'is_active' => true
            ],
            [
                'name' => 'Lain-lain',
                'code' => 'LL',
                'description' => 'Dokumen lainnya',
                'color' => '#9e9e9e',
                'icon' => 'fa-ellipsis-h',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('12 categories created successfully!');
    }
}