<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManagedCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->slug === 'admin';
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'company_size' => ['required', 'string', 'max:50'],
            'founded_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'company_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
