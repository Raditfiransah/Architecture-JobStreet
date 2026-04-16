<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ArsitekProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ArsitekProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        // Basic info
        'first_name',
        'last_name',
        'bio',
        'specialization',
        'years_experience',
        // Education
        'is_student',
        'education_institution',
        'degree_type',
        // Location & employment
        'status_pekerjaan',
        'location',
        // Skills
        'software_skills',
        'preferences',
        // Credentials & links
        'license_number',
        'resume_url',
        'portfolio_url',
        'external_portfolio_url',
        // Documents
        'identity_document_url',
        'license_document_url',
        // Verification
        'verification_status',
        'verification_note',
        'verified_at',
    ];

    protected $casts = [
        'is_student'      => 'boolean',
        'software_skills' => 'array',
        'preferences'     => 'array',
        'years_experience'=> 'integer',
        'verified_at'     => 'datetime',
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
