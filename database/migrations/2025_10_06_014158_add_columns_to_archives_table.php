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
        Schema::table('archives', function (Blueprint $table) {
            // Cek apakah kolom belum ada sebelum ditambahkan
            if (!Schema::hasColumn('archives', 'unit')) {
                $table->string('unit', 100)->after('jenis_arsip')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'pengirim')) {
                $table->string('pengirim')->after('judul')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'tanggal_surat')) {
                $table->date('tanggal_surat')->after('tanggal_arsip')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'file_name')) {
                $table->string('file_name')->after('file_path')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'file_size')) {
                $table->unsignedBigInteger('file_size')->after('file_name')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'file_type')) {
                $table->string('file_type', 50)->after('file_size')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'priority')) {
                $table->enum('priority', ['Biasa', 'Penting', 'Sangat Penting', 'Segera'])
                      ->default('Biasa')
                      ->after('unit');
            }
            
            if (!Schema::hasColumn('archives', 'tags')) {
                $table->string('tags')->after('keterangan')->nullable();
            }
            
            if (!Schema::hasColumn('archives', 'is_favorite')) {
                $table->boolean('is_favorite')->default(false)->after('tags');
            }
            
            if (!Schema::hasColumn('archives', 'category_id')) {
                $table->unsignedBigInteger('category_id')->after('jenis_arsip')->nullable();
            }
        });

        // Tambahkan foreign key jika tabel categories sudah ada
        if (Schema::hasTable('categories')) {
            Schema::table('archives', function (Blueprint $table) {
                if (!Schema::hasColumn('archives', 'category_id')) {
                    return; // Skip jika kolom belum ada
                }
                
                // Cek apakah foreign key belum ada
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('archives');
                
                $hasCategoryFk = false;
                foreach ($foreignKeys as $foreignKey) {
                    if (in_array('category_id', $foreignKey->getLocalColumns())) {
                        $hasCategoryFk = true;
                        break;
                    }
                }
                
                if (!$hasCategoryFk) {
                    $table->foreign('category_id')
                          ->references('id')
                          ->on('categories')
                          ->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Drop foreign key dulu jika ada
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('archives');
            
            foreach ($foreignKeys as $foreignKey) {
                if (in_array('category_id', $foreignKey->getLocalColumns())) {
                    $table->dropForeign([$foreignKey->getName()]);
                }
            }
            
            // Drop columns
            $columnsToCheck = [
                'category_id',
                'is_favorite',
                'tags',
                'priority',
                'file_type',
                'file_size',
                'file_name',
                'tanggal_surat',
                'pengirim',
                'unit'
            ];
            
            $existingColumns = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('archives', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};