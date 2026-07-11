<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'vacancy' => ['required', 'integer', 'min:1'],
            'job_type' => ['required', 'string', 'max:50'],
            'workplace' => ['required', 'string', 'max:50'],
            'employment_status' => ['required', 'string', 'max:50'],
            'experience_level' => ['required', 'string', 'max:100'],
            'education_level' => ['required', 'string', 'max:100'],
            'salary_type' => ['required', 'string', 'max:50'],
            'salary_min' => ['required', 'integer', 'min:0'],
            'salary_max' => ['required', 'integer', 'gte:salary_min'],
            'location' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date', 'after:today'],
            'description' => ['required', 'string'],
            'responsibilities' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'benefits' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
