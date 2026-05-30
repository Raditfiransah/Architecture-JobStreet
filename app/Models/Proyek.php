<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'budget',
        'category',
        'location',
        'attachment_path',
        'status',
    ];

    /**
     * Get the client that posted the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the proposals (bids) submitted for this project.
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'proyek_id');
    }
}
