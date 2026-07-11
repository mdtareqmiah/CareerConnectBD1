<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\JobApplication::class);
    }

    public function rules(): array
    {
        $profileId = optional($this->user()?->jobSeekerProfile)->id;

        return [
            'job_id' => [
                'required',
                'integer',
                Rule::exists('job_listings', 'id')->where(function ($query) {
                    $query->where('status', 'published');
                }),
            ],
            'resume_id' => [
                'required',
                'integer',
                Rule::exists('resumes', 'id')->where(function ($query) use ($profileId) {
                    $query->where('job_seeker_profile_id', $profileId);
                }),
            ],
            'cover_letter' => ['required', 'string', 'min:50', 'max:3000'],
        ];
    }
}
