<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'resume_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
