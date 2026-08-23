<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('gambar');
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->foreignId('diunggah_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('urutan');
            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_galleries');
    }
};
