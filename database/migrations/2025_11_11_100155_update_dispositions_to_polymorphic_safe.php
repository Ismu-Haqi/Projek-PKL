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
        // STEP 1: Tambah kolom polymorphic HANYA jika belum ada
        if (!Schema::hasColumn('dispositions', 'disposable_type')) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->string('disposable_type')->nullable()->after('nomor_disposisi');
            });
            echo "✅ Kolom disposable_type berhasil ditambahkan\n";
        } else {
            echo "ℹ️  Kolom disposable_type sudah ada, skip...\n";
        }
        
        if (!Schema::hasColumn('dispositions', 'disposable_id')) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->unsignedBigInteger('disposable_id')->nullable()->after('disposable_type');
            });
            echo "✅ Kolom disposable_id berhasil ditambahkan\n";
        } else {
            echo "ℹ️  Kolom disposable_id sudah ada, skip...\n";
        }
        
        // STEP 2: Migrate data dari archive_id ke polymorphic (jika ada)
        if (Schema::hasColumn('dispositions', 'archive_id')) {
            $migratedCount = DB::table('dispositions')
                ->whereNotNull('archive_id')
                ->whereNull('disposable_type') // Hanya migrate yang belum dimigrate
                ->update([
                    'disposable_type' => 'App\Models\Archive',
                    'disposable_id' => DB::raw('archive_id')
                ]);
            
            echo "✅ {$migratedCount} data berhasil dimigrate ke polymorphic\n";
        }
        
        // STEP 3: Hapus kolom archive_id lama (jika ada dan semua data sudah dimigrate)
        if (Schema::hasColumn('dispositions', 'archive_id')) {
            // Cek apakah masih ada data yang belum dimigrate
            $remaining = DB::table('dispositions')
                ->whereNotNull('archive_id')
                ->whereNull('disposable_type')
                ->count();
            
            if ($remaining === 0) {
                // Cek foreign key yang ada
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'dispositions'
                    AND TABLE_SCHEMA = DATABASE()
                    AND COLUMN_NAME = 'archive_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($foreignKeys)) {
                    foreach ($foreignKeys as $fk) {
                        try {
                            DB::statement("ALTER TABLE dispositions DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                            echo "✅ Foreign key {$fk->CONSTRAINT_NAME} berhasil dihapus\n";
                        } catch (\Exception $e) {
                            echo "⚠️  Gagal hapus foreign key {$fk->CONSTRAINT_NAME}: {$e->getMessage()}\n";
                        }
                    }
                } else {
                    echo "ℹ️  Tidak ada foreign key untuk archive_id\n";
                }
                
                // Hapus kolom archive_id
                Schema::table('dispositions', function (Blueprint $table) {
                    $table->dropColumn('archive_id');
                });
                echo "✅ Kolom archive_id berhasil dihapus\n";
            } else {
                echo "⚠️  Masih ada {$remaining} data yang belum dimigrate, archive_id belum dihapus\n";
            }
        } else {
            echo "ℹ️  Kolom archive_id sudah tidak ada\n";
        }
        
        // STEP 4: Tambah index untuk performa (jika belum ada)
        $indexName = 'dispositions_disposable_type_disposable_id_index';
        $indexes = DB::select("SHOW INDEX FROM dispositions WHERE Key_name = ?", [$indexName]);
        
        if (empty($indexes)) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->index(['disposable_type', 'disposable_id']);
            });
            echo "✅ Index polymorphic berhasil ditambahkan\n";
        } else {
            echo "ℹ️  Index polymorphic sudah ada, skip...\n";
        }
        
        // STEP 5: Make disposable columns NOT NULL (hanya jika semua data valid)
        $nullCount = DB::table('dispositions')
            ->whereNull('disposable_type')
            ->orWhereNull('disposable_id')
            ->count();
        
        if ($nullCount === 0) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->string('disposable_type')->nullable(false)->change();
                $table->unsignedBigInteger('disposable_id')->nullable(false)->change();
            });
            echo "✅ Kolom polymorphic berhasil diubah menjadi NOT NULL\n";
        } else {
            echo "⚠️  Masih ada {$nullCount} data dengan disposable NULL, kolom tetap NULLABLE\n";
        }
        
        echo "\n🎉 Migration selesai!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // STEP 1: Tambah kembali kolom archive_id jika belum ada
        if (!Schema::hasColumn('dispositions', 'archive_id')) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->foreignId('archive_id')->nullable()->after('nomor_disposisi')
                      ->constrained('archives')->onDelete('cascade');
            });
        }
        
        // STEP 2: Restore data dari polymorphic ke archive_id
        DB::table('dispositions')
            ->where('disposable_type', 'App\Models\Archive')
            ->whereNotNull('disposable_id')
            ->update([
                'archive_id' => DB::raw('disposable_id')
            ]);
        
        // STEP 3: Hapus index polymorphic
        $indexName = 'dispositions_disposable_type_disposable_id_index';
        $indexes = DB::select("SHOW INDEX FROM dispositions WHERE Key_name = ?", [$indexName]);
        
        if (!empty($indexes)) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->dropIndex(['disposable_type', 'disposable_id']);
            });
        }
        
        // STEP 4: Hapus kolom polymorphic
        Schema::table('dispositions', function (Blueprint $table) {
            if (Schema::hasColumn('dispositions', 'disposable_type')) {
                $table->dropColumn('disposable_type');
            }
            if (Schema::hasColumn('dispositions', 'disposable_id')) {
                $table->dropColumn('disposable_id');
            }
        });
        
        echo "\n✅ Rollback selesai!\n";
    }
};