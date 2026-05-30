<?php

declare(strict_types=1);

namespace App\Http\Requests\Marketplace;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'arsitek';
    }

    public function rules(): array
    {
        return [
            'bid_amount' => ['required', 'numeric', 'min:0'],
            'estimated_time' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,zip,jpg,png,doc,docx', 'max:10240'],
        ];
    }
}
