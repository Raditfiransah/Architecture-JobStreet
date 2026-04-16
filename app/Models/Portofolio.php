<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portofolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'project_date',
        'link',
        'thumbnail',
        'images',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'project_date' => 'date',
    ];

    /**
     * Get the user that owns the portfolio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
