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
     * Relasi ke Category
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

    /**
     * ✅ TAMBAHAN BARU - Alias untuk creator (untuk laporan)
     * Ini adalah alias dari uploader untuk kompatibilitas dengan ReportController
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ✅ TAMBAHAN BARU - Relasi ke Disposition
     */
    public function dispositions()
    {
        return $this->hasMany(Disposition::class);
    }

    /**
     * ✅ TAMBAHAN BARU - Scope: Filter by creator
     */
    public function scopeByCreator($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * ✅ TAMBAHAN BARU - Scope: Filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * ✅ TAMBAHAN BARU - Scope: Filter by unit
     */
    public function scopeByUnit($query, $unit)
    {
        return $query->where('unit', $unit);
    }

    /**
     * ✅ TAMBAHAN BARU - Scope: Active archives (favorite)
     */
    public function scopeFavorite($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * ✅ TAMBAHAN BARU - Get formatted file size
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * ✅ TAMBAHAN BARU - Get file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}