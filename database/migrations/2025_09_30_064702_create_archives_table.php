<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_surat', 100)->unique();
            $table->string('judul');
            $table->text('keterangan')->nullable();
            $table->string('jenis_arsip', 100); // Kategori
            $table->string('unit', 100); // Unit/Bidang - KOLOM BARU
            $table->date('tanggal_arsip');
            $table->string('file_path');
            $table->string('file_name')->nullable(); // Nama file original
            $table->bigInteger('file_size')->nullable(); // Ukuran file dalam bytes
            $table->string('file_type', 50)->nullable(); // PDF, DOCX, dll
            $table->boolean('is_favorite')->default(false); // Toggle favorite
            $table->integer('download_count')->default(0); // Hitung download
            $table->integer('view_count')->default(0); // Hitung views
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};