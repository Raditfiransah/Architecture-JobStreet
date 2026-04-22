<?php

declare(strict_types=1);

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'client';
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'min:3', 'max:100'],
            'email'             => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->id)],
            'phone'             => ['required', 'string', 'max:30'], // Telephone aktif, required
            'address'           => ['required', 'string', 'max:500'], // Domisili, required
            'client_type'       => ['required', 'string', 'max:50'], // Required
            'project_interests' => ['nullable', 'array'],
            'project_interests.*'=> ['string', 'max:50'],
            'budget_range'      => ['nullable', 'string', 'max:50'],
        ];
    }
}
