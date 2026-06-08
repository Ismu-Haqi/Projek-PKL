<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_laporan');
            $table->json('parameter')->nullable();
            $table->string('judul');
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->timestamp('diajukan_at')->nullable();
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users');
            $table->timestamp('divalidasi_at')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->string('tte_token')->nullable()->unique();
            $table->timestamps();
            $table->index('status');
            $table->index('diajukan_oleh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pengajuan');
    }
};
