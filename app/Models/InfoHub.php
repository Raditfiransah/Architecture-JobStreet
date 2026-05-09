<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoHub extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'judul',
        'kategori',
        'deskripsi',
        'gambar_poster',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
