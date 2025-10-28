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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_asset')->unique(); // AST/01/2024/0001
            $table->string('nama'); // Nama aset
            $table->string('kategori'); // Komputer, Printer, Proyektor, dll
            $table->string('merk')->nullable(); // Dell, HP, Epson, dll
            $table->string('tipe')->nullable(); // Model/tipe aset
            $table->string('serial_number')->nullable(); // Serial number
            $table->text('spesifikasi')->nullable(); // Detail spesifikasi
            
            // Status & Kondisi
            $table->enum('kondisi', ['baik', 'cukup', 'kurang', 'rusak'])->default('baik');
            $table->enum('status', ['tersedia', 'digunakan', 'maintenance', 'rusak'])->default('tersedia');
            
            // Lokasi & Unit
            $table->string('lokasi')->nullable(); // Ruang IT, Ruang Rapat, dll
            $table->string('unit')->nullable(); // Diskominfo, Sekretariat, dll
            
            // Pembelian & Garansi
            $table->date('tanggal_pembelian')->nullable();
            $table->decimal('harga_pembelian', 15, 2)->nullable();
            $table->integer('masa_garansi')->nullable(); // Dalam bulan
            $table->date('tanggal_garansi_berakhir')->nullable();
            
            // Penanggung Jawab
            $table->string('penanggung_jawab')->nullable();
            
            // File & QR Code
            $table->string('foto')->nullable(); // Path foto aset
            $table->string('qr_code')->nullable(); // Path QR code
            
            // Keterangan
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('kode_asset');
            $table->index('kategori');
            $table->index('status');
            $table->index('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};