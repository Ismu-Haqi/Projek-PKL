<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Relasi ke disposisi asal (null = arsip manual biasa)
            $table->foreignId('from_disposition_id')
                  ->nullable()
                  ->after('is_favorite')
                  ->constrained('dispositions')
                  ->onDelete('set null')
                  ->comment('ID disposisi yang menghasilkan arsip ini secara otomatis');

            // Sumber arsip: manual atau dari disposisi
            $table->enum('sumber', ['manual', 'disposisi'])
                  ->default('manual')
                  ->after('from_disposition_id')
                  ->comment('Asal arsip: manual (upload biasa) atau disposisi (otomatis)');

            // Nomor surat dari surat masuk (opsional, jika ada)
            $table->foreignId('from_incoming_letter_id')
                  ->nullable()
                  ->after('sumber')
                  ->constrained('incoming_letters')
                  ->onDelete('set null')
                  ->comment('ID surat masuk asal jika arsip berasal dari alur surat');

            $table->index('from_disposition_id');
            $table->index('sumber');
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropForeign(['from_disposition_id']);
            $table->dropForeign(['from_incoming_letter_id']);
            $table->dropColumn(['from_disposition_id', 'sumber', 'from_incoming_letter_id']);
        });
    }
};