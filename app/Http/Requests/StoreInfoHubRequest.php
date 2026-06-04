<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInfoHubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => ['required', Rule::in(['Event', 'Sayembara', 'Magang'])],
            'gambar_poster' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'gambar_poster.required' => 'Poster wajib diunggah.',
            'gambar_poster.image' => 'Format file tidak didukung atau terlalu besar.',
            'gambar_poster.mimes' => 'Format file tidak didukung atau terlalu besar.',
            'gambar_poster.max' => 'Format file tidak didukung atau terlalu besar.',
        ];
    }
}
