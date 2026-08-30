<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->foreignId('jadwal_retensi_id')->nullable()->after('category_id')
                  ->constrained('jadwal_retensi_arsip')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropForeign(['jadwal_retensi_id']);
            $table->dropColumn('jadwal_retensi_id');
        });
    }
};
