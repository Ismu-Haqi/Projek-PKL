<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_disposisi',
        'disposable_type',
        'disposable_id',
        'from_user_id',
        'to_user_id',
        'final_recipient_id',
        'subject',
        'instruction',
        'priority',
        'status',
        'forwarding_status',
        'deadline',
        'notes',
        'forwarding_note',
        'completion_file',
        'completion_description',
        'read_at',
        'completed_at',
        'forwarded_at',
        'forwarded_from_id',
        'forwarded_to_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'read_at' => 'datetime',
        'completed_at' => 'datetime',
        'forwarded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method untuk handle file deletion
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($disposition) {
            // Hapus file bukti penyelesaian saat disposisi dihapus
            if ($disposition->completion_file && Storage::disk('public')->exists($disposition->completion_file)) {
                Storage::disk('public')->delete($disposition->completion_file);
            }
        });
    }

    /**
     * Generate nomor disposisi otomatis
     */
    public static function generateNomorDisposisi()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastDisposition = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastDisposition ? intval(substr($lastDisposition->nomor_disposisi, -4)) + 1 : 1;
        
        return sprintf('DISP/%s/%s/%04d', $month, $year, $number);
    }

    /**
     * Polymorphic relationship
     */
    public function disposable()
    {
        return $this->morphTo();
    }

    /**
     * Relasi ke User (Pengirim)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Relasi ke User (Penerima)
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Relasi ke User (Penerima Akhir - untuk forwarding)
     */
    public function finalRecipient()
    {
        return $this->belongsTo(User::class, 'final_recipient_id');
    }

    /**
     * Relasi ke Disposition yang di-forward dari
     */
    public function forwardedFrom()
    {
        return $this->belongsTo(Disposition::class, 'forwarded_from_id');
    }

    /**
     * Relasi ke Disposition yang di-forward ke
     */
    public function forwardedTo()
    {
        return $this->hasOne(Disposition::class, 'forwarded_from_id');
    }

    /**
     * Accessor untuk mendapatkan tipe item
     */
    public function getItemTypeAttribute()
    {
        if ($this->disposable_type === 'App\Models\Archive') {
            return 'Arsip';
        } elseif ($this->disposable_type === 'App\Models\Asset') {
            return 'Aset';
        }
        return 'Unknown';
    }

    /**
     * Accessor untuk mendapatkan identifier item
     */
    public function getItemIdentifierAttribute()
    {
        if (!$this->disposable) {
            return '-';
        }
        
        if ($this->disposable_type === 'App\Models\Archive') {
            return $this->disposable->nomor_surat ?? '-';
        } elseif ($this->disposable_type === 'App\Models\Asset') {
            return $this->disposable->kode_asset ?? '-';
        }
        return '-';
    }

    /**
     * Accessor untuk mendapatkan nama item
     */
    public function getItemNameAttribute()
    {
        if (!$this->disposable) {
            return 'Item tidak ditemukan';
        }
        
        if ($this->disposable_type === 'App\Models\Archive') {
            return $this->disposable->judul ?? 'Tidak ada judul';
        } elseif ($this->disposable_type === 'App\Models\Asset') {
            return $this->disposable->nama ?? 'Tidak ada nama';
        }
        return 'Unknown';
    }

    /**
     * Accessor untuk item reference
     */
    public function getItemAttribute()
    {
        return $this->disposable;
    }

    /**
     * Get completion file URL
     */
    public function getCompletionFileUrlAttribute()
    {
        if ($this->completion_file && Storage::disk('public')->exists($this->completion_file)) {
            return Storage::url($this->completion_file);
        }
        return null;
    }

    /**
     * Get completion file name
     */
    public function getCompletionFileNameAttribute()
    {
        if ($this->completion_file) {
            return basename($this->completion_file);
        }
        return null;
    }

    /**
     * Get completion file size (in KB)
     */
    public function getCompletionFileSizeAttribute()
    {
        if ($this->completion_file && Storage::disk('public')->exists($this->completion_file)) {
            return round(Storage::disk('public')->size($this->completion_file) / 1024, 2);
        }
        return null;
    }

    /**
     * Get completion file extension
     */
    public function getCompletionFileExtensionAttribute()
    {
        if ($this->completion_file) {
            return strtoupper(pathinfo($this->completion_file, PATHINFO_EXTENSION));
        }
        return null;
    }

    /**
     * Check if disposition needs forwarding
     */
    public function needsForwarding()
    {
        return $this->forwarding_status === 'pending_forward';
    }

    /**
     * Check if disposition has been forwarded
     */
    public function isForwarded()
    {
        return $this->forwarding_status === 'forwarded' && $this->forwardedTo !== null;
    }

    /**
     * Check if this is a forwarded disposition
     */
    public function isForwardedDisposition()
    {
        return $this->forwardedFrom !== null;
    }

    /**
     * Get the ultimate sender (original sender)
     */
    public function getUltimateSenderAttribute()
    {
        if ($this->forwardedFrom) {
            return $this->forwardedFrom->fromUser;
        }
        return $this->fromUser;
    }

    /**
     * Get the ultimate recipient (final recipient or current to_user)
     */
    public function getUltimateRecipientAttribute()
    {
        return $this->finalRecipient ?? $this->toUser;
    }

    /**
     * Check if disposition has completion proof
     */
    public function hasCompletionProof()
    {
        return !empty($this->completion_file) || !empty($this->completion_description);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope untuk filter berdasarkan item type
     */
    public function scopeItemType($query, $itemType)
    {
        if ($itemType === 'arsip') {
            return $query->where('disposable_type', 'App\Models\Archive');
        } elseif ($itemType === 'aset') {
            return $query->where('disposable_type', 'App\Models\Asset');
        }
        return $query;
    }

    /**
     * Scope untuk disposisi yang diterima user tertentu
     */
    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('to_user_id', $userId);
    }

    /**
     * Scope untuk disposisi yang dikirim user tertentu
     */
    public function scopeSentBy($query, $userId)
    {
        return $query->where('from_user_id', $userId);
    }

    /**
     * Scope untuk disposisi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Cek apakah disposisi sudah terlambat
     */
    public function isOverdue()
    {
        if (!$this->deadline) {
            return false;
        }
        
        return Carbon::now()->gt($this->deadline) && $this->status !== 'completed';
    }

    /**
     * Cek apakah disposisi sudah dibaca
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Get status label dengan warna
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['text' => 'Menunggu', 'color' => 'yellow'],
            'in_progress' => ['text' => 'Diproses', 'color' => 'blue'],
            'completed' => ['text' => 'Selesai', 'color' => 'green'],
            'rejected' => ['text' => 'Ditolak', 'color' => 'red'],
        ];

        return $labels[$this->status] ?? ['text' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Get priority label dengan warna
     */
    public function getPriorityLabelAttribute()
    {
        $labels = [
            'urgent' => ['text' => 'Sangat Mendesak', 'color' => 'red'],
            'high' => ['text' => 'Mendesak', 'color' => 'orange'],
            'normal' => ['text' => 'Biasa', 'color' => 'blue'],
            'low' => ['text' => 'Tidak Mendesak', 'color' => 'gray'],
        ];

        return $labels[$this->priority] ?? ['text' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Get forwarding status label
     */
    public function getForwardingStatusLabelAttribute()
    {
        $labels = [
            'direct' => ['text' => 'Langsung', 'color' => 'blue'],
            'pending_forward' => ['text' => 'Menunggu Penerusan', 'color' => 'yellow'],
            'forwarded' => ['text' => 'Diteruskan', 'color' => 'green'],
        ];

        return $labels[$this->forwarding_status] ?? ['text' => 'Unknown', 'color' => 'gray'];
    }

    public function archive()
{
    if ($this->disposable_type === 'App\Models\Archive') {
        return $this->belongsTo(Archive::class, 'disposable_id');
    }
    return $this->belongsTo(Archive::class, 'disposable_id')->whereRaw('1 = 0');
}

    /**
     * Get sisa hari deadline
     */
    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->deadline) {
            return null;
        }

        return Carbon::now()->diffInDays($this->deadline, false);
    }
}