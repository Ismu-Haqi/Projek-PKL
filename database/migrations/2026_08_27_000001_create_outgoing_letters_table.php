<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->enum('sifat', ['biasa', 'penting', 'segera', 'rahasia'])->default('biasa');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'terkirim', 'menunggu_tte', 'ditandatangani'])->default('draft');
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();

            $table->index('tanggal_surat');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
    }
};
