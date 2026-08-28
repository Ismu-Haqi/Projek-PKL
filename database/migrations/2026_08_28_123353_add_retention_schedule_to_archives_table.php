<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            if (!Schema::hasColumn('archives', 'retention_schedule_id')) {
                $table->foreignId('retention_schedule_id')->nullable()
                      ->after('category_id')
                      ->constrained('retention_schedules')->onDelete('set null');
            }
            if (!Schema::hasColumn('archives', 'tanggal_inaktif')) {
                $table->date('tanggal_inaktif')->nullable()->after('tanggal_retensi');
            }
            if (!Schema::hasColumn('archives', 'nasib_akhir_arsip')) {
                $table->enum('nasib_akhir_arsip', ['musnah', 'permanen', 'dinilai_kembali'])
                      ->nullable()->after('tanggal_inaktif');
            }
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropConstrainedForeignId('retention_schedule_id');
            $table->dropColumn(['tanggal_inaktif', 'nasib_akhir_arsip']);
        });
    }
};
