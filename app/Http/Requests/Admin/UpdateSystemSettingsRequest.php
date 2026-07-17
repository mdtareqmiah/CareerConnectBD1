<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSystemSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->slug === 'admin';
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_email' => ['required', 'email', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'default_timezone' => ['required', 'string', Rule::in(timezone_identifiers_list())],
            'pagination_size' => ['required', 'integer', 'min:5', 'max:100'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'maintenance_mode' => $this->boolean('maintenance_mode'),
        ]);
    }
}
