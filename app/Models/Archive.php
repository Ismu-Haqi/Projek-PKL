<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 'archives';

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_arsip',
        'judul',
        'pengirim',
        'unit',
        'jenis_arsip',
        'category_id',
        'priority',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'keterangan',
        'tags',
        'is_favorite'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_arsip' => 'date',
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * ✅ TAMBAHKAN INI - Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi ke User (uploader)
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}