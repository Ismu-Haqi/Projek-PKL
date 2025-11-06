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
        // Ubah enum role untuk menambahkan 'pimpinan'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'pimpinan') DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama (admin, staff saja)
        // CATATAN: Ini akan error jika ada user dengan role 'pimpinan'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff') DEFAULT 'staff'");
    }
};