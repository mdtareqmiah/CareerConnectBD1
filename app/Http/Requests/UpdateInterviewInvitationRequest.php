<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->slug === 'employer';
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'interview_at' => ['required', 'date'],
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,accepted,declined,cancelled'],
        ];
    }
}
