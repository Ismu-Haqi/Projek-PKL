<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('checked_by')->constrained('users')->onDelete('cascade');
            $table->string('kondisi_saat_cek'); // baik, cukup, kurang, rusak
            $table->string('lokasi_saat_cek')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('kondisi_berubah')->default(false); // beda dari kondisi tercatat sebelumnya
            $table->timestamp('checked_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_checks');
    }
};