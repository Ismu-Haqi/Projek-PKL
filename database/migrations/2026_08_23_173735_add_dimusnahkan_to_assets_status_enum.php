<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('tersedia', 'digunakan', 'dipinjam', 'maintenance', 'rusak', 'dimusnahkan') DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `assets` MODIFY `status` ENUM('tersedia', 'digunakan', 'dipinjam', 'maintenance', 'rusak') DEFAULT 'tersedia'");
    }
};
