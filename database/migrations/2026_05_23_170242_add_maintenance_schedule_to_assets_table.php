<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->date('jadwal_perawatan_selanjutnya')
                  ->nullable()
                  ->after('keterangan')
                  ->comment('Jadwal perawatan rutin berikutnya');

            $table->string('jenis_perawatan')
                  ->nullable()
                  ->after('jadwal_perawatan_selanjutnya')
                  ->comment('Contoh: Servis AC, Ganti Oli Genset, Kalibrasi, dll');

            $table->date('terakhir_dirawat')
                  ->nullable()
                  ->after('jenis_perawatan')
                  ->comment('Tanggal terakhir perawatan dilakukan');

            $table->integer('interval_perawatan_hari')
                  ->nullable()
                  ->after('terakhir_dirawat')
                  ->comment('Interval perawatan dalam hari, misal: 90 = setiap 3 bulan');

            $table->text('catatan_perawatan')
                  ->nullable()
                  ->after('interval_perawatan_hari')
                  ->comment('Catatan perawatan terakhir');

            $table->index('jadwal_perawatan_selanjutnya');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['jadwal_perawatan_selanjutnya']);
            $table->dropColumn([
                'jadwal_perawatan_selanjutnya',
                'jenis_perawatan',
                'terakhir_dirawat',
                'interval_perawatan_hari',
                'catatan_perawatan',
            ]);
        });
    }
};
