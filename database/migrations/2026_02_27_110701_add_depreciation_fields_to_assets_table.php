<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Cek apakah kolom nilai_residu belum ada, baru buat
            if (!Schema::hasColumn('assets', 'nilai_residu')) {
                $table->decimal('nilai_residu', 15, 2)->nullable()->after('harga_pembelian');
            }
            
            // Cek apakah kolom umur_ekonomis belum ada, baru buat
            if (!Schema::hasColumn('assets', 'umur_ekonomis')) {
                $table->integer('umur_ekonomis')->nullable()->comment('Dalam tahun')->after('nilai_residu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'nilai_residu')) {
                $table->dropColumn('nilai_residu');
            }
            if (Schema::hasColumn('assets', 'umur_ekonomis')) {
                $table->dropColumn('umur_ekonomis');
            }
        });
    }
};
