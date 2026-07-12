<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeBuilderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->slug === 'job-seeker';
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->decodeJsonFields([
            'personal_information',
            'education',
            'experience',
            'skills',
            'projects',
            'certifications',
            'languages',
            'references',
            'social_links',
        ]));
    }

    private function decodeJsonFields(array $fields): array
    {
        $decoded = [];

        foreach ($fields as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $json = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $decoded[$field] = $json;
                }
            }
        }

        return $decoded;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'professional_summary' => ['nullable', 'string'],
            'personal_information' => ['nullable', 'array'],
            'personal_information.first_name' => ['nullable', 'string', 'max:100'],
            'personal_information.last_name' => ['nullable', 'string', 'max:100'],
            'personal_information.email' => ['nullable', 'email', 'max:255'],
            'personal_information.phone' => ['nullable', 'string', 'max:25'],
            'personal_information.linkedin_url' => ['nullable', 'url', 'max:255'],
            'personal_information.github_url' => ['nullable', 'url', 'max:255'],
            'personal_information.portfolio_url' => ['nullable', 'url', 'max:255'],
            'personal_information.website_url' => ['nullable', 'url', 'max:255'],
            'education' => ['nullable', 'array'],
            'education.*.school' => ['nullable', 'string', 'max:255'],
            'education.*.degree' => ['nullable', 'string', 'max:255'],
            'education.*.field_of_study' => ['nullable', 'string', 'max:255'],
            'education.*.start_date' => ['nullable', 'date_format:Y-m'],
            'education.*.end_date' => ['nullable', 'date_format:Y-m'],
            'education.*.notes' => ['nullable', 'string'],
            'experience' => ['nullable', 'array'],
            'experience.*.company' => ['nullable', 'string', 'max:255'],
            'experience.*.title' => ['nullable', 'string', 'max:255'],
            'experience.*.start_date' => ['nullable', 'date_format:Y-m'],
            'experience.*.end_date' => ['nullable', 'date_format:Y-m'],
            'experience.*.description' => ['nullable', 'string'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'string', 'max:255'],
            'projects' => ['nullable', 'array'],
            'projects.*.name' => ['nullable', 'string', 'max:255'],
            'projects.*.link' => ['nullable', 'url', 'max:255'],
            'projects.*.description' => ['nullable', 'string'],
            'certifications' => ['nullable', 'array'],
            'certifications.*.name' => ['nullable', 'string', 'max:255'],
            'certifications.*.issuer' => ['nullable', 'string', 'max:255'],
            'certifications.*.date' => ['nullable', 'string', 'max:255'],
            'languages' => ['nullable', 'array'],
            'languages.*.name' => ['nullable', 'string', 'max:255'],
            'references' => ['nullable', 'array'],
            'references.*.name' => ['nullable', 'string', 'max:255'],
            'references.*.position' => ['nullable', 'string', 'max:255'],
            'references.*.contact' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.github' => ['nullable', 'url', 'max:255'],
            'social_links.portfolio' => ['nullable', 'url', 'max:255'],
            'social_links.website' => ['nullable', 'url', 'max:255'],
            'template' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:draft,published'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
