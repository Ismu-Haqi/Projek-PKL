<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Update existing data from 'maintenance' to 'diperbaiki'
        DB::table('assets')
            ->where('status', 'maintenance')
            ->update(['status' => 'tersedia']); // Temporary change to avoid enum error
        
        // Step 2: Modify ENUM column to use 'diperbaiki' instead of 'maintenance'
        DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('tersedia', 'digunakan', 'diperbaiki', 'rusak') NOT NULL DEFAULT 'tersedia'");
        
        // Step 3: Update back to 'diperbaiki' 
        // (jika ada data yang sebelumnya 'maintenance')
        // Karena sudah diubah ke 'tersedia', tidak perlu step ini
        // Atau jika ingin langsung set ke diperbaiki:
        // DB::table('assets')->where('status', 'tersedia')->update(['status' => 'diperbaiki']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change 'diperbaiki' back to temporary value
        DB::table('assets')
            ->where('status', 'diperbaiki')
            ->update(['status' => 'tersedia']);
        
        // Step 2: Revert ENUM column to use 'maintenance'
        DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('tersedia', 'digunakan', 'maintenance', 'rusak') NOT NULL DEFAULT 'tersedia'");
        
        // Step 3: Update back to 'maintenance'
        DB::table('assets')
            ->where('status', 'tersedia')
            ->update(['status' => 'maintenance']);
    }
};