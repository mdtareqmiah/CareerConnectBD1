<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', 'string', 'in:Beginner,Intermediate,Advanced,Expert'],
            'years_of_experience' => ['nullable', 'numeric', 'min:0', 'max:60'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
