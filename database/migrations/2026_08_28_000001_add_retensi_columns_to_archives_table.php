<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->date('tanggal_retensi')->nullable()->after('tanggal_arsip');
            $table->boolean('retensi_notif_mendekati_terkirim')->default(false)->after('tanggal_retensi');
            $table->boolean('retensi_notif_kedaluwarsa_terkirim')->default(false)->after('retensi_notif_mendekati_terkirim');
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn(['tanggal_retensi', 'retensi_notif_mendekati_terkirim', 'retensi_notif_kedaluwarsa_terkirim']);
        });
    }
};
