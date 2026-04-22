<?php

declare(strict_types=1);

namespace App\Http\Requests\Arsitek;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'arsitek';
    }

    public function rules(): array
    {
        return [
            'first_name'             => ['required', 'string', 'min:3', 'max:100'],
            'last_name'              => ['nullable', 'string', 'max:100'],
            'bio'                    => ['nullable', 'string', 'max:500'],
            'specialization'         => ['required', 'string', 'max:100'], // Required per SKPL
            'years_experience'       => ['required', 'integer', 'min:0'], // Required per SKPL
            'status_pekerjaan'       => ['nullable', 'string', 'max:100'],
            'is_student'             => ['boolean'],
            'location'               => ['nullable', 'string', 'max:200'],
            'education_institution'  => ['nullable', 'string', 'max:200'],
            'degree_type'            => ['nullable', 'string', 'max:100'],
            'software_skills'        => ['nullable', 'array'],
            'software_skills.*'      => ['string', 'max:50'],
            'license_number'         => ['nullable', 'string', 'max:100'],
            'external_portfolio_url' => ['nullable', 'url', 'max:500'],
            'preferences'            => ['nullable', 'array'],
        ];
    }
}
