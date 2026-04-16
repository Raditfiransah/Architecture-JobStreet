<?php

namespace App\Models;

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
    ];

    protected function casts(): array
    {
        return [
            'syarat' => 'array',
            'tanggung_jawab' => 'array',
            'rating' => 'decimal:1',
            'deadline' => 'date',
        ];
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
