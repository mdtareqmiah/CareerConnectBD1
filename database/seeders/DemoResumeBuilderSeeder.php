<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\ResumeBuilder;
use Illuminate\Database\Seeder;

class DemoResumeBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = JobSeekerProfile::with(['educations', 'experiences', 'skills'])->get();

        foreach ($profiles as $profile) {
            $summary = 'Professional with experience building modern web applications, scalable APIs, and polished user experiences for startups and enterprise teams.';

            $educationData = $profile->educations->map(fn ($education) => [
                'degree' => $education->degree,
                'field_of_study' => $education->field_of_study,
                'institution_name' => $education->institution_name,
                'board_or_university' => $education->board_or_university,
                'passing_year' => $education->passing_year,
                'result' => $education->result,
            ])->values()->all();

            $experienceData = $profile->experiences->map(fn ($experience) => [
                'company_name' => $experience->company_name,
                'job_title' => $experience->job_title,
                'employment_type' => $experience->employment_type,
                'location' => $experience->location,
                'start_date' => $experience->start_date?->format('Y-m-d'),
                'end_date' => $experience->end_date?->format('Y-m-d'),
                'currently_working' => $experience->currently_working,
                'job_description' => $experience->job_description,
            ])->values()->all();

            $skillsData = $profile->skills->map(fn ($skill) => [
                'skill_name' => $skill->skill_name ?: $skill->name,
                'proficiency_level' => $skill->proficiency_level ?: 'Intermediate',
                'years_of_experience' => (int) ($skill->years_of_experience ?? 2),
            ])->values()->all();

            $projects = [
                [
                    'name' => 'Admin Portal Revamp',
                    'description' => 'Redesigned a business dashboard with role-based access, analytics, and workflow automation.',
                ],
                [
                    'name' => 'API Modernization',
                    'description' => 'Migrated legacy endpoints to a scalable Laravel-based API with improved performance.',
                ],
            ];

            $certifications = [
                [
                    'name' => 'Laravel Certification',
                    'issuer' => 'Laravel Community',
                    'year' => 2024,
                ],
            ];

            $languages = [
                ['name' => 'English', 'proficiency' => 'Fluent'],
                ['name' => 'Bangla', 'proficiency' => 'Native'],
            ];

            ResumeBuilder::updateOrCreate(
                ['job_seeker_profile_id' => $profile->id, 'title' => 'Professional Resume'],
                [
                    'professional_summary' => $summary,
                    'personal_information' => [
                        'full_name' => trim($profile->first_name . ' ' . $profile->last_name),
                        'email' => $profile->user?->email,
                        'phone' => $profile->phone,
                        'city' => $profile->city,
                        'country' => $profile->country,
                    ],
                    'education' => $educationData,
                    'experience' => $experienceData,
                    'skills' => $skillsData,
                    'projects' => $projects,
                    'certifications' => $certifications,
                    'languages' => $languages,
                    'references' => [],
                    'social_links' => [
                        'linkedin' => $profile->linkedin_url,
                        'github' => $profile->github_url,
                        'portfolio' => $profile->portfolio_url,
                    ],
                    'template' => 'modern',
                    'status' => 'ready_for_preview',
                    'is_default' => true,
                ]
            );
        }
    }
}
