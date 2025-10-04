<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100)->unique();
            $table->string('judul');
            $table->string('jenis_arsip', 100);
            $table->date('tanggal_arsip');
            $table->string('file_path'); // Path ke file yang diunggah
            $table->text('keterangan')->nullable();
            // Foreign key ke tabel users (siapa yang mengunggah/bertanggung jawab)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
