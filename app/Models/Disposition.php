<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_disposisi',
        'archive_id',
        'from_user_id',
        'to_user_id',
        'subject',
        'instruction',
        'priority',
        'status',
        'deadline',
        'notes',
        'read_at',
        'completed_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'read_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
     * Relasi ke Archive
     */
    public function archive()
    {
        return $this->belongsTo(Archive::class);
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
            'urgent' => ['text' => 'Sangat Urgent', 'color' => 'red'],
            'high' => ['text' => 'Penting', 'color' => 'orange'],
            'normal' => ['text' => 'Normal', 'color' => 'blue'],
            'low' => ['text' => 'Rendah', 'color' => 'gray'],
        ];

        return $labels[$this->priority] ?? ['text' => 'Unknown', 'color' => 'gray'];
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