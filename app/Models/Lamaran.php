<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lamaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lowongan_id',
        'status',
        'cv_path',
        'portfolio_path',
        'notes',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    /**
     * Get the user that applied.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job (lowongan) that was applied for.
     */
    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }
}
