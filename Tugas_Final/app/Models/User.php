<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // PENTING: Import BelongsTo
use Illuminate\Database\Eloquent\Relations\HasOne;    // PENTING: Import HasOne
use Illuminate\Database\Eloquent\Relations\HasMany;   // PENTING: Import HasMany

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
        'join_date',
        'division_id',
        'initial_leave_quota',
        'is_active',
        'username', // PENTING: Tambahkan username
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'join_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Define the relationship with the Division this user belongs to.
     */
    public function division(): BelongsTo // <-- FUNGSI INI HILANG ATAU SALAH
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Define the relationship with the Division this user leads (if they are a Leader).
     */
    public function leadDivision(): HasOne
    {
        return $this->hasOne(Division::class, 'leader_id');
    }

    /**
     * Get all leave applications submitted by the user.
     */
    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }

    /**
     * Get the leave quotas for the user.
     */
    public function leaveQuotas(): HasMany
    {
        return $this->hasMany(LeaveQuota::class);
    }

    /**
     * Get the current year's leave quota for the user.
     */
    public function currentYearQuota()
    {
        return $this->leaveQuotas()->where('year', date('Y'))->first();
    }

    // Role checking methods (for cleaner code in controllers/policies/middleware)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHRD(): bool
    {
        return $this->role === 'hrd';
    }

    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}