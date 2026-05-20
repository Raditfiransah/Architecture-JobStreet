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
        'identity_document_url',
        'domicile_document_url',
        'project_ownership_document_url',
        'verification_status',
        'verification_note',
        'verified_at',
    ];

    protected $casts = [
        'project_interests' => 'array',
        'verified_at'       => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
}
