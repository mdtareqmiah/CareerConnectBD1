<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:2', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt'],
        ];
    }
}
