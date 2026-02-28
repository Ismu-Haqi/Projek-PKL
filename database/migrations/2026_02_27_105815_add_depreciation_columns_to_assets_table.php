<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Menambah kolom untuk perhitungan penyusutan
            $table->decimal('harga_beli', 15, 2)->default(0)->after('nama');
            $table->decimal('nilai_residu', 15, 2)->default(0)->after('harga_beli');
            $table->integer('umur_ekonomis')->default(0)->comment('Dalam satuan tahun')->after('nilai_residu');
            
            // Pastikan Anda sudah punya kolom tanggal pembelian/perolehan. 
            // Jika belum ada, uncomment baris di bawah ini:
            // $table->date('tanggal_pembelian')->nullable()->after('umur_ekonomis');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'nilai_residu', 'umur_ekonomis']);
        });
    }
};