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
        'location',
        'phone',
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

    public function clientProfile(): HasOne
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function emailVerificationCodes(): HasMany
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    public function portofolios(): HasMany
    {
        return $this->hasMany(Portofolio::class)->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class)->orderBy('created_at', 'desc');
    }

    public function lowongans(): HasMany
    {
        return $this->hasMany(Lowongan::class);
    }

    public function proyeks(): HasMany
    {
        return $this->hasMany(Proyek::class)->orderBy('created_at', 'desc');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class)->orderBy('created_at', 'desc');
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

    public function dashboardRoute()
    {
        return match($this->role) {
            'admin' => route('admin.dashboard'),
            'perusahaan' => route('perusahaan.profile'),
            'client' => route('client.profile'),
            'arsitek' => route('arsitek.profile'),
            default => route('home'),
        };
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', ['token' => $token, 'email' => $this->email]);

        Mail::to($this->email)->send(new ResetPasswordMail($this->name, $url));
    }
}
