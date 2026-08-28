<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RetentionSchedule extends Model
{
    use HasFactory;

    protected $table = 'retention_schedules';

    protected $fillable = [
        'category_id',
        'kode_klasifikasi',
        'retensi_aktif_tahun',
        'retensi_inaktif_tahun',
        'nasib_akhir',
        'dasar_hukum',
        'keterangan',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'retensi_aktif_tahun' => 'integer',
        'retensi_inaktif_tahun' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archives()
    {
        return $this->hasMany(Archive::class, 'retention_schedule_id');
    }

    public function getTotalRetensiTahunAttribute(): int
    {
        return (int) $this->retensi_aktif_tahun + (int) $this->retensi_inaktif_tahun;
    }

    public static function labelNasibAkhir(string $kode): string
    {
        return match ($kode) {
            'musnah'          => 'Dimusnahkan',
            'permanen'        => 'Permanen (disimpan selamanya)',
            'dinilai_kembali' => 'Dinilai Kembali',
            default           => ucfirst($kode),
        };
    }

    /**
     * Hitung tanggal arsip menjadi inaktif (berakhirnya masa aktif),
     * berdasarkan tanggal arsip dan aturan JRA ini.
     */
    public function hitungTanggalInaktif($tanggalArsip): Carbon
    {
        return Carbon::parse($tanggalArsip)->addYears($this->retensi_aktif_tahun);
    }

    /**
     * Hitung tanggal retensi akhir (waktunya arsip dimusnahkan / dinilai
     * kembali / dipermanenkan), berdasarkan tanggal arsip dan aturan JRA ini.
     */
    public function hitungTanggalRetensi($tanggalArsip): Carbon
    {
        return Carbon::parse($tanggalArsip)->addYears($this->totalRetensiTahun);
    }
}
