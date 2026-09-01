<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPengajuan extends Model
{
    protected $table = 'laporan_pengajuan';

    protected $fillable = [
        'jenis_laporan', 'parameter', 'judul',
        'diajukan_oleh', 'diajukan_at',
        'divalidasi_oleh', 'divalidasi_at',
        'status', 'catatan', 'tte_token',
    ];

    protected $casts = [
        'parameter'     => 'array',
        'diajukan_at'   => 'datetime',
        'divalidasi_at' => 'datetime',
    ];

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'menunggu'  => ['text' => 'Menunggu Validasi', 'color' => 'yellow'],
            'disetujui' => ['text' => 'Disetujui & TTE',   'color' => 'green'],
            'ditolak'   => ['text' => 'Ditolak',           'color' => 'red'],
            default     => ['text' => '-',                  'color' => 'gray'],
        };
    }

    public function isApproved(): bool
    {
        return $this->status === 'disetujui';
    }

    public function canDownload(int $userId): bool
    {
        if (!$this->isApproved()) return false;
        $user = User::find($userId);
        return $this->diajukan_oleh === $userId || $user?->role === 'pimpinan';
    }

    public static function labelJenis(string $jenis): string
    {
        return match ($jenis) {
            'arsip'       => 'Laporan Arsip Digital',
            'disposisi'   => 'Laporan Disposisi Surat',
            'aset'        => 'Laporan Manajemen Aset',
            'user'        => 'Laporan Aktivitas User',
            'unit'        => 'Laporan Unit Kerja',
            'penyusutan'  => 'Laporan Penyusutan Aset',
            'peminjaman'  => 'Laporan Peminjaman Aset',
            'maintenance' => 'Laporan Pemeliharaan Aset',
            'surat-masuk' => 'Laporan Surat Masuk',
            default       => 'Laporan ' . ucfirst($jenis),
        };
    }

    public static function jenisYangBisaDiajukan(): array
    {
        return [
            'arsip', 'disposisi', 'aset', 'user',
            'unit', 'penyusutan', 'peminjaman',
            'maintenance', 'surat-masuk', 'pemusnahan', 'agenda-surat',
            'laporan-surat-keluar', 'beban-kerja-pimpinan',
        ];
    }
}
