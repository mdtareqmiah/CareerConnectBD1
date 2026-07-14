<?php

namespace App\Http\Requests;

use App\Models\Feedback;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(Feedback::TYPES)],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'attachment' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt'],
        ];
    }
}
