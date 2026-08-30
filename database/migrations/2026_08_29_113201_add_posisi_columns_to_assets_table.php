<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Posisi pin pada denah, disimpan dalam persen (0-100) supaya
            // tetap akurat di berbagai ukuran layar/tampilan gambar denah.
            $table->decimal('posisi_x', 5, 2)->nullable()->after('lokasi');
            $table->decimal('posisi_y', 5, 2)->nullable()->after('posisi_x');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['posisi_x', 'posisi_y']);
        });
    }
};
