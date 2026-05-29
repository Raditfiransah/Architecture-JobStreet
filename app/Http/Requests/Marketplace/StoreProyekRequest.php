<?php

declare(strict_types=1);

namespace App\Http\Requests\Marketplace;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'client';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'budget' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:100'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,zip,jpg,png,doc,docx', 'max:10240'],
        ];
    }
}
