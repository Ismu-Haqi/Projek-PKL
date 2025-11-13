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
        Schema::table('dispositions', function (Blueprint $table) {
            // Hapus foreign key archive_id yang lama
            $table->dropForeign(['archive_id']);
            
            // Ubah archive_id menjadi nullable
            $table->unsignedBigInteger('archive_id')->nullable()->change();
            
            // Tambah kolom polymorphic untuk disposable (bisa arsip atau aset)
            $table->string('disposable_type')->nullable()->after('archive_id');
            $table->unsignedBigInteger('disposable_id')->nullable()->after('disposable_type');
            
            // Tambah index untuk polymorphic relation
            $table->index(['disposable_type', 'disposable_id']);
        });
        
        // Migrate data lama: pindahkan archive_id ke disposable
        DB::table('dispositions')->whereNotNull('archive_id')->update([
            'disposable_type' => 'App\\Models\\Archive',
            'disposable_id' => DB::raw('archive_id')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            // Kembalikan data dari disposable ke archive_id
            DB::table('dispositions')
                ->where('disposable_type', 'App\\Models\\Archive')
                ->update(['archive_id' => DB::raw('disposable_id')]);
            
            // Hapus kolom polymorphic
            $table->dropIndex(['disposable_type', 'disposable_id']);
            $table->dropColumn(['disposable_type', 'disposable_id']);
            
            // Kembalikan foreign key
            $table->foreign('archive_id')->references('id')->on('archives')->onDelete('cascade');
        });
    }
};