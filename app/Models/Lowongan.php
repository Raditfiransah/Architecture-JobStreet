<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = [
        'user_id',
        'posisi',
        'perusahaan',
        'kota',
        'tipe',
        'gaji',
        'inisial',
        'rating',
        'deskripsi',
        'syarat',
        'tanggung_jawab',
        'status',
        'deadline',
        'tanggal_mulai',
        'batas_lamaran',
    ];

    protected function casts(): array
    {
        return [
            'syarat' => 'array',
            'tanggung_jawab' => 'array',
            'rating' => 'decimal:1',
            'deadline' => 'date',
            'tanggal_mulai' => 'date',
            'batas_lamaran' => 'date',
        ];
    }

    public function scopePubliclyAvailable(Builder $query): Builder
    {
        $today = today();

        return $query
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('batas_lamaran', '>=', $today);
    }

    public function isAvailableForApplication(): bool
    {
        $today = today();

        return $this->status === 'aktif'
            && $this->tanggal_mulai !== null
            && $this->batas_lamaran !== null
            && $this->tanggal_mulai->lte($today)
            && $this->batas_lamaran->gte($today);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lamarans()
    {
        return $this->hasMany(Lamaran::class);
    }
}
