<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'company_website',
        'company_logo_url',
        'company_desc',
        'industry',
        'company_size',
        'location',
        'business_fields',
        'founded_year',
        'nib_number',
        'identity_document_url',
        'verification_status',
        'verification_note',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'business_fields'     => 'array',
            'verified_at'         => 'datetime',
            'founded_year'        => 'integer',
        ];
    }

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
