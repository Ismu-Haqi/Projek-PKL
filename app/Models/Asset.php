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
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'tanggal_garansi_berakhir' => 'date',
        'harga_pembelian' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate Kode Asset Otomatis
     */
    public static function generateKodeAsset($kategori = 'AST')
    {
        $year = date('Y');
        $month = date('m');
        
        // Ambil kode asset terakhir untuk tahun dan bulan ini
        $lastAsset = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        // Ekstrak nomor urut dari kode terakhir
        $number = $lastAsset ? intval(substr($lastAsset->kode_asset, -4)) + 1 : 1;
        
        // Format: AST/MM/YYYY/0001
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
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'tersedia' => ['text' => 'Tersedia', 'color' => 'green'],
            'digunakan' => ['text' => 'Digunakan', 'color' => 'blue'],
            'maintenance' => ['text' => 'Maintenance', 'color' => 'yellow'],
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
     * Get sisa garansi dalam bulan
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
     * Get umur asset dalam tahun
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
     * Relasi ke history peminjaman (jika nanti ada fitur peminjaman)
     */
    public function borrowHistory()
    {
        return $this->hasMany(AssetBorrow::class);
    }

    /**
     * Relasi ke maintenance history (jika nanti ada fitur maintenance)
     */
    public function maintenanceHistory()
    {
        return $this->hasMany(AssetMaintenance::class);
    }
}