<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployerJobIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role?->slug === 'employer';
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'closed', 'expired'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
        ];
    }

    public function validatedFilters(): array
    {
        return $this->validated();
    }
}
