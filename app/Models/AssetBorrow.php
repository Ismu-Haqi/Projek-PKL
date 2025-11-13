<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AssetBorrow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_peminjaman',
        'asset_id',
        'borrower_id',
        'borrower_unit',
        'approved_by',
        'tanggal_pengajuan',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'keperluan',
        'catatan_peminjam',
        'catatan_admin',
        'catatan_pengembalian',
        'kondisi_pinjam',
        'kondisi_kembali',
        'foto_pinjam',
        'foto_kembali',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate Kode Peminjaman Otomatis
     */
    public static function generateKodePeminjaman()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastBorrow = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastBorrow ? intval(substr($lastBorrow->kode_peminjaman, -4)) + 1 : 1;
        
        // Format: BRW/MM/YYYY/0001
        return sprintf('BRW/%s/%s/%04d', $month, $year, $number);
    }

    /**
     * Relasi ke Asset
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Relasi ke Borrower (Staff)
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * Relasi ke Admin yang approve
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk status pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk status approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk status borrowed (sedang dipinjam)
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    /**
     * Scope untuk status returned
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope untuk status overdue
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope untuk filter berdasarkan peminjam
     */
    public function scopeByBorrower($query, $borrowerId)
    {
        return $query->where('borrower_id', $borrowerId);
    }

    /**
     * Cek apakah terlambat
     */
    public function isOverdue()
    {
        if ($this->status === 'borrowed' && $this->tanggal_kembali_rencana) {
            return Carbon::now()->gt($this->tanggal_kembali_rencana);
        }
        return false;
    }

    /**
     * Get durasi peminjaman (hari)
     */
    public function getDurasiPeminjamanAttribute()
    {
        if ($this->tanggal_pinjam && $this->tanggal_kembali_rencana) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali_rencana);
        }
        return 0;
    }

    /**
     * Get lama dipinjam (hari) - aktual
     */
    public function getLamaDipinjamAttribute()
    {
        if ($this->tanggal_pinjam) {
            $endDate = $this->tanggal_kembali_aktual ?? Carbon::now();
            return $this->tanggal_pinjam->diffInDays($endDate);
        }
        return 0;
    }

    /**
     * Get keterlambatan (hari)
     */
    public function getKeterlambatanAttribute()
    {
        if ($this->status === 'returned' && $this->tanggal_kembali_aktual && $this->tanggal_kembali_rencana) {
            $days = $this->tanggal_kembali_rencana->diffInDays($this->tanggal_kembali_aktual, false);
            return max(0, $days);
        }
        
        if ($this->isOverdue()) {
            return $this->tanggal_kembali_rencana->diffInDays(Carbon::now());
        }
        
        return 0;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['text' => 'Menunggu Persetujuan', 'color' => 'yellow', 'icon' => 'clock'],
            'approved' => ['text' => 'Disetujui', 'color' => 'green', 'icon' => 'check-circle'],
            'rejected' => ['text' => 'Ditolak', 'color' => 'red', 'icon' => 'x-circle'],
            'borrowed' => ['text' => 'Sedang Dipinjam', 'color' => 'blue', 'icon' => 'arrow-right'],
            'returned' => ['text' => 'Dikembalikan', 'color' => 'green', 'icon' => 'check'],
            'overdue' => ['text' => 'Terlambat', 'color' => 'red', 'icon' => 'exclamation'],
        ];

        return $badges[$this->status] ?? ['text' => 'Unknown', 'color' => 'gray', 'icon' => 'question'];
    }

    /**
     * Update status ke overdue otomatis
     */
    public static function updateOverdueStatus()
    {
        self::where('status', 'borrowed')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->update(['status' => 'overdue']);
    }
}