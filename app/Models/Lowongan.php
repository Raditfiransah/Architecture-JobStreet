<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'syarat' => 'array',
            'tanggung_jawab' => 'array',
            'rating' => 'decimal:1',
        ];
    }
}
