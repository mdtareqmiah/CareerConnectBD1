<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'degree' => ['required', 'string', 'max:255'],
            'field_of_study' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'board_or_university' => ['nullable', 'string', 'max:255'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'result' => ['nullable', 'string', 'max:100'],
            'grading_system' => ['nullable', 'string', 'max:100'],
            'passing_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
