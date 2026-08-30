<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRetensiArsip extends Model
{
    use HasFactory;

    protected $table = 'jadwal_retensi_arsip';

    protected $fillable = [
        'kode_klasifikasi',
        'nama_klasifikasi',
        'deskripsi',
        'jangka_aktif_tahun',
        'jangka_inaktif_tahun',
        'nasib_akhir',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function arsip()
    {
        return $this->hasMany(Archive::class, 'jadwal_retensi_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function getNasibAkhirLabelAttribute(): array
    {
        return match ($this->nasib_akhir) {
            'musnah'          => ['text' => 'Musnah',          'color' => 'red'],
            'permanen'        => ['text' => 'Permanen',        'color' => 'blue'],
            'dinilai_kembali' => ['text' => 'Dinilai Kembali', 'color' => 'yellow'],
            default           => ['text' => '-',               'color' => 'gray'],
        };
    }

    public function getTotalMasaSimpanAttribute(): int
    {
        return $this->jangka_aktif_tahun + $this->jangka_inaktif_tahun;
    }
}
