<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_mutasi')->unique();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('unit_asal')->nullable();
            $table->string('lokasi_asal')->nullable();
            $table->string('unit_tujuan');
            $table->string('lokasi_tujuan')->nullable();
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->date('tanggal_mutasi');
            $table->date('tanggal_persetujuan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('alasan');
            $table->text('catatan_penolakan')->nullable();
            $table->string('berita_acara')->nullable();
            $table->timestamps();

            $table->index('asset_id');
            $table->index('status');
            $table->index('tanggal_mutasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_mutations');
    }
};
