<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_asset',
        'nama',
        'kategori',
        'merk',
        'tipe',
        'serial_number',
        'spesifikasi',
        'kondisi',
        'status',
        'lokasi',
        'unit',
        'tanggal_pembelian',
        'harga_pembelian',
        'masa_garansi',
        'tanggal_garansi_berakhir',
        'penanggung_jawab',
        'foto',
        'qr_code',
        'keterangan',
        // ── Perawatan rutin ──────────────────────────────
        'jadwal_perawatan_selanjutnya',
        'jenis_perawatan',
        'terakhir_dirawat',
        'interval_perawatan_hari',
        'catatan_perawatan',
    ];

    protected $casts = [
        'tanggal_pembelian'             => 'date',
        'tanggal_garansi_berakhir'      => 'date',
        'jadwal_perawatan_selanjutnya'  => 'date',
        'terakhir_dirawat'              => 'date',
        'harga_pembelian'               => 'decimal:2',
        'created_at'                    => 'datetime',
        'updated_at'                    => 'datetime',
    ];

    // ── Scope: aset yang jadwal perawatannya H-7 atau kurang ─────────────────
    public function scopeJatuhTempoPerawatan($query, int $days = 7)
    {
        return $query->whereNotNull('jadwal_perawatan_selanjutnya')
            ->whereRaw('DATEDIFF(jadwal_perawatan_selanjutnya, CURDATE()) BETWEEN 0 AND ?', [$days])
            ->orderBy('jadwal_perawatan_selanjutnya', 'asc');
    }

    // ── Scope: aset yang jadwal perawatannya sudah terlewat ──────────────────
    public function scopePerawatanTerlambat($query)
    {
        return $query->whereNotNull('jadwal_perawatan_selanjutnya')
            ->whereRaw('jadwal_perawatan_selanjutnya < CURDATE()')
            ->orderBy('jadwal_perawatan_selanjutnya', 'asc');
    }

    // ── Accessor: sisa hari hingga perawatan ─────────────────────────────────
    public function getSisaHariPerawatanAttribute(): ?int
    {
        if (!$this->jadwal_perawatan_selanjutnya) return null;
        return now()->startOfDay()->diffInDays($this->jadwal_perawatan_selanjutnya, false);
    }

    // ── Accessor: status perawatan ───────────────────────────────────────────
    public function getStatusPerawatanAttribute(): ?string
    {
        if (!$this->jadwal_perawatan_selanjutnya) return null;
        $sisa = $this->sisa_hari_perawatan;
        if ($sisa < 0)  return 'terlambat';
        if ($sisa <= 7) return 'segera';
        return 'aman';
    }

    /**
     * Generate Kode Asset Otomatis
     */
    public static function generateKodeAsset($kategori = 'AST')
    {
        $year = date('Y');
        $month = date('m');
        
        $lastAsset = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastAsset ? intval(substr($lastAsset->kode_asset, -4)) + 1 : 1;
        
        return sprintf('%s/%s/%s/%04d', strtoupper(substr($kategori, 0, 3)), $month, $year, $number);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan unit
     */
    public function scopeUnit($query, $unit)
    {
        return $query->where('unit', $unit);
    }

    /**
     * Scope untuk filter berdasarkan kondisi
     */
    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode_asset', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%")
              ->orWhere('merk', 'like', "%{$search}%");
        });
    }

    /**
     * ✅ FIXED: Scope untuk aset yang bisa dipinjam
     * Status ENUM yang benar: aktif, non-aktif, dalam_perbaikan, rusak, dihapus
     */
   public function scopeAvailableForBorrow($query)
{
    return $query->where('status', 'tersedia')  // ✅ Sesuai migration
                ->whereIn('kondisi', ['baik', 'cukup']);
}

/**
 * ✅ FIXED: Get status badge
 */
public function getStatusBadgeAttribute()
{
    $badges = [
        'tersedia' => ['text' => 'Tersedia', 'color' => 'green'],
        'digunakan' => ['text' => 'Digunakan', 'color' => 'blue'],
        'dipinjam' => ['text' => 'Dipinjam', 'color' => 'orange'],
        'maintenance' => ['text' => 'Pemeliharaan', 'color' => 'yellow'],
        'rusak' => ['text' => 'Rusak', 'color' => 'red'],
    ];

    return $badges[$this->status] ?? ['text' => 'Unknown', 'color' => 'gray'];
}


    /**
     * Get kondisi badge color
     */
    public function getKondisiBadgeAttribute()
    {
        $badges = [
            'baik' => ['text' => 'Baik', 'color' => 'green'],
            'cukup' => ['text' => 'Cukup', 'color' => 'blue'],
            'kurang' => ['text' => 'Kurang', 'color' => 'yellow'],
            'rusak' => ['text' => 'Rusak', 'color' => 'red'],
        ];

        return $badges[$this->kondisi] ?? ['text' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Cek apakah garansi masih berlaku
     */
    public function isGaransiBerlaku()
    {
        if (!$this->tanggal_garansi_berakhir) {
            return false;
        }
        
        return Carbon::now()->lte($this->tanggal_garansi_berakhir);
    }

    /**
     * Get sisa garansi
     */
    public function getSisaGaransiAttribute()
    {
        if (!$this->tanggal_garansi_berakhir) {
            return null;
        }

        if (!$this->isGaransiBerlaku()) {
            return 'Habis';
        }

        $months = Carbon::now()->diffInMonths($this->tanggal_garansi_berakhir);
        $days = Carbon::now()->diffInDays($this->tanggal_garansi_berakhir);

        if ($months > 0) {
            return $months . ' bulan';
        }

        return $days . ' hari';
    }

    /**
     * Get umur asset
     */
    public function getUmurAssetAttribute()
    {
        if (!$this->tanggal_pembelian) {
            return null;
        }

        $years = Carbon::now()->diffInYears($this->tanggal_pembelian);
        $months = Carbon::now()->diffInMonths($this->tanggal_pembelian) % 12;

        if ($years > 0) {
            return $years . ' tahun' . ($months > 0 ? ' ' . $months . ' bulan' : '');
        }

        return $months . ' bulan';
    }

    /**
     * Get foto URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        
        return asset('images/no-image.png');
    }

    /**
     * ✅ Relasi ke history peminjaman
     */
    public function borrowHistory()
    {
        return $this->hasMany(AssetBorrow::class)->orderBy('created_at', 'desc');
    }

    /**
     * ✅ Get peminjaman aktif saat ini
     */
    public function activeBorrow()
    {
        return $this->hasOne(AssetBorrow::class)
                    ->whereIn('status', ['approved', 'borrowed', 'overdue'])
                    ->latest();
    }

    /**
     * ✅ Cek apakah aset sedang dipinjam
     */
    public function isBorrowed()
    {
        return $this->activeBorrow()->exists();
    }

    /**
     * ✅ TAMBAHAN BARU (Poin 6 - Scan QR Cek Fisik)
     * Riwayat hasil cek fisik aset (dari fitur scan QR kamera HP).
     */
    public function checkHistory()
    {
        return $this->hasMany(AssetCheck::class)->orderBy('checked_at', 'desc');
    }

    public function latestCheck()
    {
        return $this->hasOne(AssetCheck::class)->latestOfMany('checked_at');
    }

    /**
     * ✅ FIXED: Cek apakah aset bisa dipinjam
     */
    public function canBeBorrowed()
    {
        return $this->status === 'tersedia'  // ✅ Sesuai migration
            && in_array($this->kondisi, ['baik', 'cukup'])
            && !$this->isBorrowed();
    }

    /**
     * Relasi ke maintenance history (untuk fitur future)
     */
    public function maintenanceHistory()
    {
        return $this->hasMany(AssetMaintenance::class);
    }
    
    public function getPenyusutanPerTahunAttribute()
    {
        if (!$this->umur_ekonomis || $this->umur_ekonomis == 0 || !$this->harga_pembelian) return 0;
        
        return ($this->harga_pembelian - $this->nilai_residu) / $this->umur_ekonomis;
    }

    /**
     * 2. Menghitung Berapa Tahun Aset Telah Digunakan
     */
    public function getUmurTerpakaiAttribute()
    {
        if (!$this->tanggal_pembelian) return 0;
        
        $tahunBeli = \Carbon\Carbon::parse($this->tanggal_pembelian)->year;
        $tahunSekarang = now()->year;
        $umur = $tahunSekarang - $tahunBeli;
        
        // Mentok di umur ekonomis jika sudah melebihi batas
        return $umur > $this->umur_ekonomis ? $this->umur_ekonomis : $umur;
    }

    /**
     * 3. Menghitung Nilai Valuasi Aset Saat Ini (Nilai Buku)
     */
    public function getNilaiBukuAttribute()
    {
        if (!$this->harga_pembelian) return 0;

        $akumulasiPenyusutan = $this->penyusutan_per_tahun * $this->umur_terpakai;
        $nilaiSekarang = $this->harga_pembelian - $akumulasiPenyusutan;
        
        return $nilaiSekarang < $this->nilai_residu ? $this->nilai_residu : $nilaiSekarang;
    }

    /**
     * 4. Sistem Pendukung Keputusan (SPK) Kelayakan Sederhana
     */
    public function getStatusKelayakanAttribute()
    {
        if (!$this->tanggal_pembelian || !$this->umur_ekonomis) return 'Data Belum Lengkap';
        
        if ($this->umur_terpakai >= $this->umur_ekonomis) {
            return 'Waktu Penghapusan / Lelang';
        } elseif ($this->umur_terpakai >= ($this->umur_ekonomis * 0.8)) {
            return 'Kritis (Perlu Perhatian)';
        }
        return 'Layak Pakai';
    }
}