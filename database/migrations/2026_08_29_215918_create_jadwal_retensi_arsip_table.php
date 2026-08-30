<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_retensi_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi')->unique();
            $table->string('nama_klasifikasi');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('jangka_aktif_tahun');     // masa simpan arsip aktif
            $table->unsignedInteger('jangka_inaktif_tahun');   // masa simpan arsip inaktif setelah aktif berakhir
            $table->enum('nasib_akhir', ['musnah', 'permanen', 'dinilai_kembali'])->default('musnah');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index('kode_klasifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_retensi_arsip');
    }
};
