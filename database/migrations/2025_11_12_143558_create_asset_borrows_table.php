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
        Schema::create('asset_borrows', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            
            // Relasi ke Asset
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            
            // Peminjam (Staff)
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->string('borrower_unit'); // Unit peminjam
            
            // Admin yang approve
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Tanggal
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            
            // Status: pending, approved, rejected, borrowed, returned, overdue
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue'])
                  ->default('pending');
            
            // Keterangan
            $table->text('keperluan'); // Tujuan peminjaman
            $table->text('catatan_peminjam')->nullable();
            $table->text('catatan_admin')->nullable(); // Catatan saat approve/reject
            $table->text('catatan_pengembalian')->nullable();
            
            // Kondisi
            $table->enum('kondisi_pinjam', ['baik', 'cukup', 'kurang', 'rusak'])->nullable();
            $table->enum('kondisi_kembali', ['baik', 'cukup', 'kurang', 'rusak'])->nullable();
            
            // Bukti (Optional)
            $table->string('foto_pinjam')->nullable(); // Foto saat dipinjam
            $table->string('foto_kembali')->nullable(); // Foto saat dikembalikan
            
            $table->timestamps();
            $table->softDeletes(); // Untuk history
            
            // Index untuk performa
            $table->index('status');
            $table->index('borrower_id');
            $table->index('asset_id');
            $table->index('tanggal_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_borrows');
    }
};