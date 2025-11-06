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
        'is_active',
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
     * ========================================
     * ROLE CHECKER METHODS
     * ========================================
     */

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * ✅ NEW: Check if user is pimpinan
     *
     * @return bool
     */
    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active ?? true;
    }

    /**
     * ========================================
     * NOTIFICATION RELATIONS
     * ========================================
     */

    /**
     * Relasi ke Notifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi ke unread notifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    /**
     * Get unread notifications count
     *
     * @return int
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * ========================================
     * ARCHIVE & DOCUMENT RELATIONS
     * ========================================
     */

    /**
     * Archives created by this user
     * Relasi ke arsip yang dibuat oleh user ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archives()
    {
        if (!\Schema::hasTable('archives')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(Archive::class, 'user_id');
    }

    /**
     * Incoming letters handled by this user
     * Relasi ke surat masuk yang ditangani oleh user ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * Relasi ke surat keluar yang dibuat oleh user ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * DISPOSITION RELATIONS
     * ========================================
     */

    /**
     * Dispositions sent by this user (admin only)
     * Relasi ke disposisi yang dikirim oleh user ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedDispositions()
    {
        if (!\Schema::hasTable('dispositions')) {
            return $this->hasMany(self::class)->whereRaw('1 = 0');
        }
        return $this->hasMany(Disposition::class, 'to_user_id');
    }

    /**
     * ========================================
     * QUERY SCOPES
     * ========================================
     */

    /**
     * Scope untuk filter admin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk filter staff
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    /**
     * ✅ NEW: Scope untuk filter pimpinan
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePimpinan($query)
    {
        return $query->where('role', 'pimpinan');
    }

    /**
     * Scope untuk filter active users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter inactive users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * ========================================
     * ACCESSORS & MUTATORS
     * ========================================
     */

    /**
     * Get the user's role badge color
     *
     * @return string
     */
    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'admin' => 'blue',
            'staff' => 'green',
            'pimpinan' => 'purple',
            default => 'gray'
        };
    }

    /**
     * Get the user's role display name
     *
     * @return string
     */
    public function getRoleDisplayNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'pimpinan' => 'Pimpinan',
            default => ucfirst($this->role)
        };
    }

    /**
     * Get the user's initials for avatar
     *
     * @return string
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get the user's avatar URL or default
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }
}