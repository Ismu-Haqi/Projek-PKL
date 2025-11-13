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
        // Ubah kolom role dari enum menjadi string untuk menghindari masalah dengan enum di MySQL
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('staff')->change();
        });
        
        // Atau jika ingin tetap menggunakan enum, gunakan raw SQL
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'pimpinan') DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('staff')->change();
        });
        
        // Atau jika menggunakan enum
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff') DEFAULT 'staff'");
    }
};