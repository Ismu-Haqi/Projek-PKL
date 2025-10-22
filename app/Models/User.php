<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'unit',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active ?? true;
    }

    /**
     * Relasi ke Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi ke unread notifications
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * ========================================
     * RELATIONS UNTUK USER CONTROLLER
     * ========================================
     */

    /**
     * Dispositions sent by this user (admin only)
     * Relasi ke disposisi yang dikirim oleh user ini
     */
    public function sentDispositions()
    {
        if (!\Schema::hasTable('dispositions')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

    /**
     * Dispositions received by this user (staff only)
     * Relasi ke disposisi yang diterima oleh user ini
     */
    public function receivedDispositions()
    {
        if (!\Schema::hasTable('dispositions')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(Disposition::class, 'to_user_id');
    }

    /**
     * ✅ FIX: Archives created by this user
     * Kolom yang benar adalah 'user_id', bukan 'created_by'
     */
    public function archives()
    {
        if (!\Schema::hasTable('archives')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(Archive::class, 'user_id'); // ✅ FIXED: user_id
    }

    /**
     * Incoming letters handled by this user
     */
    public function incomingLetters()
    {
        if (!\Schema::hasTable('incoming_letters')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(IncomingLetter::class, 'received_by');
    }

    /**
     * Outgoing letters created by this user
     */
    public function outgoingLetters()
    {
        if (!\Schema::hasTable('outgoing_letters')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(OutgoingLetter::class, 'created_by');
    }

    /**
     * ========================================
     * QUERY SCOPES
     * ========================================
     */

    /**
     * Scope untuk filter admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk filter staff
     */
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    /**
     * Scope untuk filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}