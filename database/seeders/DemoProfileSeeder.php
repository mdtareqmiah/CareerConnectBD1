<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        if (! $jobSeekerRole) {
            $this->command->warn('Job seeker role not found. Skipping profile seeding.');
            return;
        }

        $jobSeekers = User::where('role_id', $jobSeekerRole->id)->get();

        $profiles = [
            [
                'first_name' => 'Md Tareq',
                'last_name' => 'Miah',
                'phone' => '+8801712345678',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'professional_title' => 'Laravel Developer',
                'professional_summary' => 'Laravel and PHP developer with experience building scalable web applications, APIs, and admin portals for startups and growing businesses.',
                'current_job_title' => 'Senior Laravel Developer',
                'current_company' => 'ABC Technologies',
                'years_of_experience' => 3,
                'expected_salary' => 45000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Dhaka',
                'linkedin_url' => 'https://www.linkedin.com/in/tareq-miah',
                'github_url' => 'https://github.com/tareq-miah',
                'portfolio_url' => 'https://tareqmiah.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
            [
                'first_name' => 'Asif',
                'last_name' => 'Ahmed',
                'phone' => '+8801812345679',
                'city' => 'Chattogram',
                'state' => 'Chattogram',
                'country' => 'Bangladesh',
                'professional_title' => 'Frontend Developer',
                'professional_summary' => 'Frontend developer focused on responsive UI implementation, modern JavaScript patterns, and interactive dashboards for SaaS products.',
                'current_job_title' => 'Frontend Developer',
                'current_company' => 'DevHub',
                'years_of_experience' => 2,
                'expected_salary' => 35000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Chattogram',
                'linkedin_url' => 'https://www.linkedin.com/in/asif-ahmed',
                'github_url' => 'https://github.com/asif-ahmed',
                'portfolio_url' => 'https://asifahmed.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
            [
                'first_name' => 'Sunia',
                'last_name' => 'Akter',
                'phone' => '+8801912345680',
                'city' => 'Sylhet',
                'state' => 'Sylhet',
                'country' => 'Bangladesh',
                'professional_title' => 'Backend Developer',
                'professional_summary' => 'Backend engineer experienced in building REST APIs, database design, and service-oriented architecture for enterprise applications.',
                'current_job_title' => 'Backend Developer',
                'current_company' => 'TechSoft',
                'years_of_experience' => 4,
                'expected_salary' => 60000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Sylhet',
                'linkedin_url' => 'https://www.linkedin.com/in/sunia-akter',
                'github_url' => 'https://github.com/sunia-akter',
                'portfolio_url' => 'https://suniaakter.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
            [
                'first_name' => 'Rahim',
                'last_name' => 'Uddin',
                'phone' => '+8801612345681',
                'city' => 'Khulna',
                'state' => 'Khulna',
                'country' => 'Bangladesh',
                'professional_title' => 'Software Engineer',
                'professional_summary' => 'Software engineer with strong debugging, testing, and delivery discipline for high-traffic web platforms.',
                'current_job_title' => 'Software Engineer',
                'current_company' => 'CodeLab',
                'years_of_experience' => 5,
                'expected_salary' => 50000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Khulna',
                'linkedin_url' => 'https://www.linkedin.com/in/rahim-uddin',
                'github_url' => 'https://github.com/rahim-uddin',
                'portfolio_url' => 'https://rahimuddin.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
            [
                'first_name' => 'Karim',
                'last_name' => 'Hasan',
                'phone' => '+8801512345682',
                'city' => 'Rajshahi',
                'state' => 'Rajshahi',
                'country' => 'Bangladesh',
                'professional_title' => 'Full Stack Developer',
                'professional_summary' => 'Full stack developer comfortable shipping end-to-end features, API integrations, and polished admin experiences.',
                'current_job_title' => 'Full Stack Developer',
                'current_company' => 'Nexora',
                'years_of_experience' => 3,
                'expected_salary' => 40000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Rajshahi',
                'linkedin_url' => 'https://www.linkedin.com/in/karim-hasan',
                'github_url' => 'https://github.com/karim-hasan',
                'portfolio_url' => 'https://karimhasan.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
            [
                'first_name' => 'Nusrat',
                'last_name' => 'Jahan',
                'phone' => '+8801412345683',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'professional_title' => 'PHP Developer',
                'professional_summary' => 'PHP-focused developer with experience in legacy modernization, bug fixing, and building maintainable business applications.',
                'current_job_title' => 'PHP Developer',
                'current_company' => 'BrightCoders',
                'years_of_experience' => 2,
                'expected_salary' => 30000,
                'preferred_job_type' => 'Full Time',
                'preferred_location' => 'Dhaka',
                'linkedin_url' => 'https://www.linkedin.com/in/nusrat-jahan',
                'github_url' => 'https://github.com/nusrat-jahan',
                'portfolio_url' => 'https://nusratjahan.dev',
                'is_profile_completed' => true,
                'is_available_for_work' => true,
            ],
        ];

        foreach ($jobSeekers as $index => $user) {
            $profileData = $profiles[$index] ?? $profiles[0];

            JobSeekerProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $profileData['first_name'],
                    'last_name' => $profileData['last_name'],
                    'phone' => $profileData['phone'],
                    'city' => $profileData['city'],
                    'state' => $profileData['state'],
                    'country' => $profileData['country'],
                    'professional_title' => $profileData['professional_title'],
                    'professional_summary' => $profileData['professional_summary'],
                    'current_job_title' => $profileData['current_job_title'],
                    'current_company' => $profileData['current_company'],
                    'years_of_experience' => $profileData['years_of_experience'],
                    'expected_salary' => $profileData['expected_salary'],
                    'preferred_job_type' => $profileData['preferred_job_type'],
                    'preferred_location' => $profileData['preferred_location'],
                    'linkedin_url' => $profileData['linkedin_url'],
                    'github_url' => $profileData['github_url'],
                    'portfolio_url' => $profileData['portfolio_url'],
                    'profile_photo' => null,
                    'is_profile_completed' => $profileData['is_profile_completed'],
                    'is_available_for_work' => $profileData['is_available_for_work'],
                ]
            );
        }
    }
}
