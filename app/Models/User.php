<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_verified',
        'avatar_url',
        'email_verified_at',
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function arsitekProfile(): HasOne
    {
        return $this->hasOne(ArsitekProfile::class);
    }

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function emailVerificationCodes(): HasMany
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isArsitek(): bool
    {
        return $this->role === 'arsitek';
    }

    public function isPerusahaan(): bool
    {
        return $this->role === 'perusahaan';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'arsitek' => 'arsitek.profile',
            'perusahaan' => 'perusahaan.profile',
            'client' => 'client.profile',
            default => 'home',
        };
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', ['token' => $token, 'email' => $this->email]);

        Mail::to($this->email)->send(new ResetPasswordMail($this->name, $url));
    }
}
