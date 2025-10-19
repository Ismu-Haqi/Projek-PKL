<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'unit',
        'phone',
        'avatar',
        'is_active',  // TAMBAHAN: status aktif user
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
        // Check if table exists to prevent error
        if (!\Schema::hasTable('dispositions')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0'); // Return empty relation
        }
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

    /**
     * Dispositions received by this user (staff only)
     * Relasi ke disposisi yang diterima oleh user ini
     */
    public function receivedDispositions()
    {
        // Check if table exists to prevent error
        if (!\Schema::hasTable('dispositions')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0'); // Return empty relation
        }
        return $this->hasMany(Disposition::class, 'to_user_id');
    }

    /**
     * Archives created by this user
     * Relasi ke arsip yang dibuat oleh user ini
     */
    public function archives()
    {
        // Check if table exists to prevent error
        if (!\Schema::hasTable('archives')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0'); // Return empty relation
        }
        return $this->hasMany(Archive::class, 'created_by');
    }

    /**
     * Incoming letters handled by this user
     * Relasi ke surat masuk yang ditangani user ini
     */
    public function incomingLetters()
    {
        // Check if table exists to prevent error
        if (!\Schema::hasTable('incoming_letters')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0'); // Return empty relation
        }
        return $this->hasMany(IncomingLetter::class, 'received_by');
    }

    /**
     * Outgoing letters created by this user
     * Relasi ke surat keluar yang dibuat user ini
     */
    public function outgoingLetters()
    {
        // Check if table exists to prevent error
        if (!\Schema::hasTable('outgoing_letters')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0'); // Return empty relation
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