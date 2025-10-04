<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar
    protected $table = 'archives'; 

    /**
     * The attributes that are mass assignable.
     * Harus mencantumkan semua kolom yang diisi di ArchiveController::store().
     */
    protected $fillable = [
        'user_id',
        'nomor_surat', 
        'judul',
        'keterangan',    // Untuk Deskripsi
        'jenis_arsip',   // Untuk Kategori
        'unit',          // Untuk Unit/Bidang
        'tanggal_arsip', // Untuk Tanggal Dokumen
        'file_path',     // Untuk Path File
        'is_favorite',   // Jika Anda memiliki kolom ini
    ];

    // Kolom tanggal yang harus diubah ke instance Carbon
    protected $dates = ['tanggal_arsip'];
}