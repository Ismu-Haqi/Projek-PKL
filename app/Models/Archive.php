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
        'tanggal_retensi',
        'retensi_notif_mendekati_terkirim',
        'retensi_notif_kedaluwarsa_terkirim',
        'retention_schedule_id',
        'tanggal_inaktif',
        'nasib_akhir_arsip',
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
        'tanggal_retensi' => 'date',
        'tanggal_inaktif' => 'date',
        'retensi_notif_mendekati_terkirim' => 'boolean',
        'retensi_notif_kedaluwarsa_terkirim' => 'boolean',
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * ✅ TAMBAHAN BARU (Poin 2 - Jadwal Retensi Arsip)
     * Aturan JRA resmi yang dipakai untuk menghitung retensi arsip ini.
     */
    public function retentionSchedule()
    {
        return $this->belongsTo(RetentionSchedule::class, 'retention_schedule_id');
    }

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

    /**
     * ✅ Status retensi arsip berbasis Jadwal Retensi Arsip (JRA) resmi.
     * Arsip dengan nasib akhir "permanen" tidak pernah dianggap kedaluwarsa.
     */
    public function getStatusRetensiAttribute(): array
    {
        if (!$this->tanggal_retensi) {
            return ['text' => 'Belum diatur', 'color' => 'gray'];
        }

        if ($this->nasib_akhir_arsip === 'permanen') {
            return ['text' => 'Permanen', 'color' => 'blue'];
        }

        $today = now()->startOfDay();
        $batas = $this->tanggal_retensi->startOfDay();

        if ($today->gt($batas)) {
            $label = $this->nasib_akhir_arsip === 'dinilai_kembali'
                ? 'Waktunya Dinilai Kembali'
                : 'Sudah Kedaluwarsa';
            return ['text' => $label, 'color' => 'red'];
        }

        if ($today->diffInDays($batas, false) <= 30) {
            return ['text' => 'Mendekati Retensi', 'color' => 'yellow'];
        }

        return ['text' => 'Aktif', 'color' => 'green'];
    }

    public function scopeRetensiMendekati($query, $hari = 30)
    {
        return $query->whereNotNull('tanggal_retensi')
            ->where(function ($q) {
                $q->whereNull('nasib_akhir_arsip')->orWhere('nasib_akhir_arsip', '!=', 'permanen');
            })
            ->whereDate('tanggal_retensi', '>=', now()->toDateString())
            ->whereDate('tanggal_retensi', '<=', now()->addDays($hari)->toDateString());
    }

    public function scopeRetensiKedaluwarsa($query)
    {
        return $query->whereNotNull('tanggal_retensi')
            ->where(function ($q) {
                $q->whereNull('nasib_akhir_arsip')->orWhere('nasib_akhir_arsip', '!=', 'permanen');
            })
            ->whereDate('tanggal_retensi', '<', now()->toDateString());
    }
}