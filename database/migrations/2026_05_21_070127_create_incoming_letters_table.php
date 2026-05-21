<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique()->comment('Nomor agenda surat masuk');
            $table->string('nomor_surat')->comment('Nomor surat dari pengirim');
            $table->date('tanggal_surat')->comment('Tanggal tertulis di surat');
            $table->date('tanggal_diterima')->comment('Tanggal surat diterima');
            $table->string('pengirim')->comment('Instansi/orang pengirim surat');
            $table->string('perihal')->comment('Perihal/pokok surat');
            $table->enum('sifat', ['biasa', 'segera', 'sangat_segera', 'rahasia'])->default('biasa');
            $table->string('kategori')->nullable()->comment('Jenis surat: undangan, permohonan, pemberitahuan, dll');
            $table->string('unit_tujuan')->nullable()->comment('Unit/bidang yang dituju');
            $table->string('file_path')->nullable()->comment('Path file scan surat');
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['belum_disposisi', 'sudah_disposisi', 'selesai'])->default('belum_disposisi');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade')->comment('User yang menginput surat');
            $table->foreignId('disposition_id')->nullable()->constrained('dispositions')->onDelete('set null')->comment('Disposisi yang dibuat dari surat ini');
            $table->timestamp('disposisi_at')->nullable()->comment('Waktu surat didisposisikan');
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_diterima');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};