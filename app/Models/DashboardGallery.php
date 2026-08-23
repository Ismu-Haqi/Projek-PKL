<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardGallery extends Model
{
    use HasFactory;

    protected $table = 'dashboard_galleries';

    protected $fillable = [
        'gambar',
        'judul',
        'deskripsi',
        'urutan',
        'aktif',
        'diunggah_oleh',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'diunggah_oleh');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUrut($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at');
    }

    public function getGambarUrlAttribute(): string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : asset('images/no-image.png');
    }
}
