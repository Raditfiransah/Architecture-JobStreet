<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsitekProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'status_pekerjaan',
        'is_student',
        'location',
        'school',
        'degree_type',
        'preferences',
        'resume_url',
        'portfolio_url',
    ];

    protected $casts = [
        'is_student' => 'boolean',
        'preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** @use HasFactory<\Database\Factories\ArsitekProfileFactory> */
    use HasFactory;
}
