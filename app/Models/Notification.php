<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'icon',
        'url',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Penerima Notifikasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope untuk notifikasi user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk notifikasi berdasarkan tipe
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Cek apakah notifikasi sudah dibaca
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Tandai notifikasi sebagai belum dibaca
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Get icon class berdasarkan tipe
     */
    public function getIconClassAttribute()
    {
        $icons = [
            'disposition' => ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'blue'],
            'archive' => ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'green'],
            'user' => ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'purple'],
            'system' => ['icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow'],
            'warning' => ['icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'red'],
        ];

        return $icons[$this->type] ?? $icons['system'];
    }

    /**
     * Get waktu relatif (5 menit lalu, 1 jam lalu, dll)
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Static method untuk create notifikasi disposisi
     */
    public static function createDispositionNotification($disposition)
    {
        return self::create([
            'user_id' => $disposition->to_user_id,
            'type' => 'disposition',
            'title' => 'Disposisi Baru',
            'message' => "Anda menerima disposisi: {$disposition->subject}",
            'data' => [
                'disposition_id' => $disposition->id,
                'from_user' => $disposition->fromUser->name,
                'priority' => $disposition->priority,
            ],
            'url' => route('staff.disposisi.show', $disposition->id),
        ]);
    }

    /**
     * Static method untuk create notifikasi arsip baru
     */
    public static function createArchiveNotification($archive, $userId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'archive',
            'title' => 'Arsip Baru Ditambahkan',
            'message' => "Arsip baru: {$archive->judul}",
            'data' => [
                'archive_id' => $archive->id,
                'category' => $archive->category->name ?? 'Uncategorized',
            ],
            'url' => route('admin.arsip.show', $archive->id),
        ]);
    }

    /**
     * Static method untuk create notifikasi disposisi selesai
     */
    public static function createDispositionCompletedNotification($disposition)
    {
        return self::create([
            'user_id' => $disposition->from_user_id,
            'type' => 'disposition',
            'title' => 'Disposisi Diselesaikan',
            'message' => "{$disposition->toUser->name} telah menyelesaikan disposisi: {$disposition->subject}",
            'data' => [
                'disposition_id' => $disposition->id,
                'completed_by' => $disposition->toUser->name,
            ],
            'url' => route('admin.disposisi.show', $disposition->id),
        ]);
    }

    /**
     * Static method untuk create notifikasi user baru
     */
    public static function createNewUserNotification($user, $adminId)
    {
        return self::create([
            'user_id' => $adminId,
            'type' => 'user',
            'title' => 'User Baru Terdaftar',
            'message' => "{$user->name} telah terdaftar sebagai {$user->role}",
            'data' => [
                'user_id' => $user->id,
                'role' => $user->role,
            ],
            'url' => route('admin.user.show', $user->id),
        ]);
    }

    /**
     * Static method untuk create notifikasi sistem
     */
    public static function createSystemNotification($userId, $title, $message)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'data' => [],
        ]);
    }
}