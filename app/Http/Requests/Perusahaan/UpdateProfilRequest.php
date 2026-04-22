<?php

declare(strict_types=1);

namespace App\Http\Requests\Perusahaan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'perusahaan';
    }

    public function rules(): array
    {
        return [
            'company_name'    => ['required', 'string', 'min:3', 'max:150'],
            'phone'           => ['required', 'string', 'max:30'], // Required per SKPL
            'location'        => ['required', 'string', 'max:200'], // Required per SKPL (Alamat Kantor)
            'company_website' => ['nullable', 'url', 'max:500'],
            'company_desc'    => ['nullable', 'string', 'max:1000'],
            'industry'        => ['nullable', 'string', 'max:100'],
            'company_size'    => ['required', 'string', 'max:50'], // Required per SKPL
            'business_fields' => ['required', 'array'], // Required per SKPL
            'business_fields.*'=> ['string', 'max:50'],
            'founded_year'    => ['required', 'integer', 'digits:4', 'min:1800', 'max:' . date('Y')], // Required per SKPL
            'nib_number'      => ['nullable', 'string', 'max:100'],
        ];
    }
}
