<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status 'ditolak' ke enum
        DB::statement("ALTER TABLE outgoing_letters MODIFY status ENUM('draft', 'menunggu_tte', 'ditandatangani', 'ditolak', 'terkirim') DEFAULT 'draft'");

        Schema::table('outgoing_letters', function (Blueprint $table) {
            $table->string('tte_token')->nullable()->after('status');
            $table->timestamp('diajukan_tte_at')->nullable()->after('tte_token');
            $table->foreignId('divalidasi_oleh')->nullable()->after('diajukan_tte_at')->constrained('users');
            $table->timestamp('divalidasi_at')->nullable()->after('divalidasi_oleh');
            $table->text('catatan_penolakan')->nullable()->after('divalidasi_at');
        });
    }

    public function down(): void
    {
        Schema::table('outgoing_letters', function (Blueprint $table) {
            $table->dropForeign(['divalidasi_oleh']);
            $table->dropColumn(['tte_token', 'diajukan_tte_at', 'divalidasi_oleh', 'divalidasi_at', 'catatan_penolakan']);
        });

        DB::statement("ALTER TABLE outgoing_letters MODIFY status ENUM('draft', 'terkirim', 'menunggu_tte', 'ditandatangani') DEFAULT 'draft'");
    }
};
