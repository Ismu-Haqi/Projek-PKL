<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM status
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('tersedia', 'digunakan', 'dipinjam', 'maintenance', 'rusak') DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('tersedia', 'digunakan', 'maintenance', 'rusak') DEFAULT 'tersedia'");
    }
};