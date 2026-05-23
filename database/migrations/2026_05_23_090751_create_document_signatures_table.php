<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique()->comment('Token unik untuk validasi dokumen');
            $table->string('document_type')->comment('Jenis laporan: arsip, disposisi, aset, dll');
            $table->string('document_title')->comment('Judul dokumen');
            $table->string('signed_by')->comment('Nama penandatangan');
            $table->string('signed_by_title')->nullable()->comment('Jabatan penandatangan');
            $table->timestamp('signed_at')->comment('Waktu dokumen dicetak/ditandatangani');
            $table->string('instansi')->nullable()->default('Dinas Komunikasi dan Informatika Kab. Barito Kuala');
            $table->json('metadata')->nullable()->comment('Data tambahan laporan');
            $table->timestamps();

            $table->index('token');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_signatures');
    }
};
