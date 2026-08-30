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
        'jadwal_retensi_id',
        'retensi_notif_mendekati_terkirim',
        'retensi_notif_kedaluwarsa_terkirim',
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
        'retensi_notif_mendekati_terkirim' => 'boolean',
        'retensi_notif_kedaluwarsa_terkirim' => 'boolean',
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

    /**
     * ✅ TAMBAHAN BARU - Status retensi arsip
     * (fondasi awal, akan disempurnakan saat fitur Jadwal Retensi Arsip dibangun penuh)
     */
    public function getStatusRetensiAttribute(): array
    {
        if (!$this->tanggal_retensi) {
            return ['text' => 'Belum diatur', 'color' => 'gray'];
        }

        $today = now()->startOfDay();
        $batas = $this->tanggal_retensi->startOfDay();

        if ($today->gt($batas)) {
            return ['text' => 'Sudah Kedaluwarsa', 'color' => 'red'];
        }

        if ($today->diffInDays($batas, false) <= 30) {
            return ['text' => 'Mendekati Retensi', 'color' => 'yellow'];
        }

        return ['text' => 'Aktif', 'color' => 'green'];
    }

    public function scopeRetensiMendekati($query, $hari = 30)
    {
        return $query->whereNotNull('tanggal_retensi')
            ->whereDate('tanggal_retensi', '>=', now()->toDateString())
            ->whereDate('tanggal_retensi', '<=', now()->addDays($hari)->toDateString());
    }

    public function scopeRetensiKedaluwarsa($query)
    {
        return $query->whereNotNull('tanggal_retensi')
            ->whereDate('tanggal_retensi', '<', now()->toDateString());
    }

    /**
     * ✅ TAMBAHAN BARU (Poin 2 revisi) - Jadwal Retensi Arsip (JRA) formal
     */
    public function jadwalRetensi()
    {
        return $this->belongsTo(JadwalRetensiArsip::class, 'jadwal_retensi_id');
    }

    /**
     * Tanggal berakhirnya masa inaktif (titik keputusan nasib akhir:
     * musnah / permanen / dinilai kembali), dihitung dari tanggal_retensi
     * (akhir masa aktif) + jangka_inaktif_tahun sesuai klasifikasi JRA.
     */
    public function getTanggalInaktifBerakhirAttribute()
    {
        if (!$this->tanggal_retensi || !$this->jadwalRetensi) {
            return null;
        }
        return $this->tanggal_retensi->copy()->addYears($this->jadwalRetensi->jangka_inaktif_tahun);
    }

    public function getNasibAkhirAttribute(): ?string
    {
        return $this->jadwalRetensi?->nasib_akhir;
    }
}