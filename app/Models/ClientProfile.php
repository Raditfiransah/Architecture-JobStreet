<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_type',
        'address',
        'project_interests',
        'budget_range',
    ];

    protected $casts = [
        'project_interests' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
