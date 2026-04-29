<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password', 'otp', 'otp_expires_at'])]
#[Hidden(['password', 'remember_token', 'otp', 'otp_expires_at'])]
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * ✅ Fallback (recommended for stability)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * 🔐 JWT Identifier
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 🔐 JWT Custom Claims
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 🔢 Generate OTP
     */
    public function generateOtp()
    {
        $this->otp = rand(100000, 999999);
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();

        return $this->otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($otp)
    {
        if ($this->otp !== $otp) {
            return false;
        }

        if (now()->gt($this->otp_expires_at)) {
            return false;
        }

        // Clear OTP after success
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->save();

        return true;
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class);
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        $roles = $this->relationLoaded('userRoles')
            ? $this->userRoles
            : $this->userRoles()->get();

        return $roles->contains(fn (UserRole $userRole) => (int) $userRole->department_id === 1
            && (int) $userRole->role_id === 1
            && (int) $userRole->position_id === 1);
    }
}
