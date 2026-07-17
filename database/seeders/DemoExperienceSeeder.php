<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\JobSeekerProfile;
use Illuminate\Database\Seeder;

class DemoExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = JobSeekerProfile::all();

        $experienceSets = [
            [
                [
                    'company_name' => 'ABC Technologies',
                    'job_title' => 'Junior Laravel Developer',
                    'employment_type' => 'Full Time',
                    'location' => 'Dhaka',
                    'start_date' => '2023-01-01',
                    'end_date' => null,
                    'currently_working' => true,
                    'job_description' => 'Developed Laravel modules, maintained APIs, and supported internal admin dashboards for client projects.',
                ],
            ],
            [
                [
                    'company_name' => 'DevHub',
                    'job_title' => 'Frontend Developer',
                    'employment_type' => 'Full Time',
                    'location' => 'Chattogram',
                    'start_date' => '2022-06-01',
                    'end_date' => '2024-01-01',
                    'currently_working' => false,
                    'job_description' => 'Built responsive interfaces and implemented interactive components for a fintech dashboard.',
                ],
            ],
            [
                [
                    'company_name' => 'TechSoft',
                    'job_title' => 'Backend Developer',
                    'employment_type' => 'Full Time',
                    'location' => 'Sylhet',
                    'start_date' => '2022-02-01',
                    'end_date' => null,
                    'currently_working' => true,
                    'job_description' => 'Worked on REST API development, database optimizations, and service integrations for enterprise clients.',
                ],
            ],
            [
                [
                    'company_name' => 'CodeLab',
                    'job_title' => 'Intern Software Engineer',
                    'employment_type' => 'Internship',
                    'location' => 'Khulna',
                    'start_date' => '2021-07-01',
                    'end_date' => '2022-01-01',
                    'currently_working' => false,
                    'job_description' => 'Supported the engineering team with debugging, testing, and deployment tasks for web applications.',
                ],
            ],
            [
                [
                    'company_name' => 'Nexora',
                    'job_title' => 'Full Stack Developer',
                    'employment_type' => 'Full Time',
                    'location' => 'Rajshahi',
                    'start_date' => '2022-03-01',
                    'end_date' => null,
                    'currently_working' => true,
                    'job_description' => 'Delivered end-to-end product features, handled third-party integrations, and improved application reliability.',
                ],
            ],
            [
                [
                    'company_name' => 'BrightCoders',
                    'job_title' => 'PHP Developer',
                    'employment_type' => 'Full Time',
                    'location' => 'Dhaka',
                    'start_date' => '2021-09-01',
                    'end_date' => '2023-03-01',
                    'currently_working' => false,
                    'job_description' => 'Maintained PHP applications, refactored legacy modules, and improved performance for business systems.',
                ],
            ],
        ];

        foreach ($profiles as $index => $profile) {
            $experienceData = $experienceSets[$index % count($experienceSets)] ?? $experienceSets[0];

            foreach ($experienceData as $experience) {
                Experience::updateOrCreate(
                    [
                        'job_seeker_profile_id' => $profile->id,
                        'company_name' => $experience['company_name'],
                        'job_title' => $experience['job_title'],
                    ],
                    [
                        'employment_type' => $experience['employment_type'],
                        'location' => $experience['location'],
                        'start_date' => $experience['start_date'],
                        'end_date' => $experience['end_date'],
                        'currently_working' => $experience['currently_working'],
                        'job_description' => $experience['job_description'],
                    ]
                );
            }
        }
    }
}
