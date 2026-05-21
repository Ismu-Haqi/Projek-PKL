<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $table = 'incoming_letters';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim',
        'perihal',
        'sifat',
        'kategori',
        'unit_tujuan',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'keterangan',
        'status',
        'uploaded_by',
        'disposition_id',
        'disposisi_at',
    ];

    protected $casts = [
        'tanggal_surat'   => 'date',
        'tanggal_diterima' => 'date',
        'disposisi_at'    => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'disposition_id');
    }

    // ─── Generate nomor agenda otomatis ──────────────────────────────────────

    public static function generateNomorAgenda(): string
    {
        $year  = date('Y');
        $month = date('m');

        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $last
            ? intval(substr($last->nomor_agenda, strrpos($last->nomor_agenda, '/') + 1)) + 1
            : 1;

        return sprintf('SM/%s/%s/%04d', $month, $year, $number);
    }

    // ─── Accessor ────────────────────────────────────────────────────────────

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }

    public function getSifatBadgeAttribute(): array
    {
        return [
            'biasa'         => ['text' => 'Biasa',        'color' => 'gray'],
            'segera'        => ['text' => 'Segera',       'color' => 'yellow'],
            'sangat_segera' => ['text' => 'Sangat Segera','color' => 'orange'],
            'rahasia'       => ['text' => 'Rahasia',      'color' => 'red'],
        ][$this->sifat] ?? ['text' => 'Biasa', 'color' => 'gray'];
    }

    public function getStatusBadgeAttribute(): array
    {
        return [
            'belum_disposisi' => ['text' => 'Belum Disposisi', 'color' => 'yellow'],
            'sudah_disposisi' => ['text' => 'Sudah Disposisi', 'color' => 'blue'],
            'selesai'         => ['text' => 'Selesai',         'color' => 'green'],
        ][$this->status] ?? ['text' => 'Unknown', 'color' => 'gray'];
    }

    // ─── Scope ───────────────────────────────────────────────────────────────

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_agenda', 'like', "%{$search}%")
              ->orWhere('nomor_surat', 'like', "%{$search}%")
              ->orWhere('pengirim', 'like', "%{$search}%")
              ->orWhere('perihal', 'like', "%{$search}%");
        });
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySifat($query, string $sifat)
    {
        return $query->where('sifat', $sifat);
    }

    // ─── Boot (hapus file ketika record dihapus) ──────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (IncomingLetter $letter) {
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
        });
    }
}