<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_destructions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pemusnahan')->unique();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->text('alasan_pemusnahan');
            $table->string('kondisi_aset')->nullable();
            $table->date('tanggal_usulan');
            $table->date('tanggal_pemusnahan')->nullable();
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->date('tanggal_persetujuan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_penolakan')->nullable();
            $table->string('berita_acara')->nullable();
            $table->timestamps();

            $table->index('asset_id');
            $table->index('status');
            $table->index('tanggal_usulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_destructions');
    }
};
