<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retention_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->unique()->constrained('categories')->onDelete('cascade');
            $table->string('kode_klasifikasi', 50)->nullable();
            $table->unsignedSmallInteger('retensi_aktif_tahun')->default(2);
            $table->unsignedSmallInteger('retensi_inaktif_tahun')->default(3);
            $table->enum('nasib_akhir', ['musnah', 'permanen', 'dinilai_kembali'])->default('musnah');
            $table->string('dasar_hukum')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retention_schedules');
    }
};
