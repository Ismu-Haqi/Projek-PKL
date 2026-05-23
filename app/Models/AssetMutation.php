<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_mutasi',
        'asset_id',
        'unit_asal',
        'lokasi_asal',
        'unit_tujuan',
        'lokasi_tujuan',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_mutasi',
        'tanggal_persetujuan',
        'status',
        'alasan',
        'catatan_penolakan',
        'berita_acara',
    ];

    protected $casts = [
        'tanggal_mutasi'      => 'date',
        'tanggal_persetujuan' => 'date',
    ];

    // ========================
    // RELASI
    // ========================

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    // ========================
    // GENERATE NOMOR MUTASI
    // ========================

    public static function generateNomor(): string
    {
        $month = date('m');
        $year  = date('Y');

        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? (intval(substr($last->nomor_mutasi, -4)) + 1) : 1;

        return sprintf('MUT/%s/%s/%04d', $month, $year, $number);
    }

    // ========================
    // BADGE HELPERS
    // ========================

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'menunggu'  => ['text' => 'Menunggu Persetujuan', 'color' => 'yellow'],
            'disetujui' => ['text' => 'Disetujui',            'color' => 'green'],
            'ditolak'   => ['text' => 'Ditolak',              'color' => 'red'],
            default     => ['text' => 'Unknown',              'color' => 'gray'],
        };
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }
}
