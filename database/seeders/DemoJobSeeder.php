<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::query()->whereNotNull('employer_id')->get();

        if ($companies->isEmpty()) {
            $this->command->warn('No companies found. Skipping demo jobs.');
            return;
        }

        $jobTemplates = [
            [
                'title' => 'Laravel Developer',
                'description' => 'We are looking for a Laravel developer who can build robust web applications and maintain high-quality code in a collaborative team environment.',
                'requirements' => 'Strong Laravel and PHP skills, experience with MySQL, REST APIs, Git, and modern web development practices.',
                'responsibilities' => 'Build features, improve APIs, troubleshoot bugs, and collaborate with designers and product teams.',
                'benefits' => 'Flexible working environment, health benefits, performance bonus, and growth opportunities.',
                'job_type' => 'Full Time',
                'workplace' => 'Onsite',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 60000,
                'salary_max' => 90000,
                'location' => 'Dhaka',
                'vacancy' => 2,
            ],
            [
                'title' => 'PHP Developer',
                'description' => 'Join our team to design, build, and optimize PHP based web applications used by thousands of users.',
                'requirements' => 'Solid PHP knowledge, experience with Laravel, MVC, MySQL, Bootstrap, and Git.',
                'responsibilities' => 'Develop backend modules, ensure code quality, and support deployment and maintenance.',
                'benefits' => 'Competitive compensation, remote flexibility, training support, and paid leaves.',
                'job_type' => 'Full Time',
                'workplace' => 'Remote',
                'employment_status' => 'Full Time',
                'experience_level' => 'Junior',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 45000,
                'salary_max' => 70000,
                'location' => 'Sylhet',
                'vacancy' => 1,
            ],
            [
                'title' => 'Backend Developer',
                'description' => 'We need a backend developer experienced in building scalable services and handling complex business logic.',
                'requirements' => 'Strong API design, database optimization, PHP/Laravel, MySQL, REST API, and Git experience required.',
                'responsibilities' => 'Create APIs, optimize database queries, and troubleshoot production issues.',
                'benefits' => 'Specialized growth plan, modern work tools, and yearly appraisals.',
                'job_type' => 'Full Time',
                'workplace' => 'Hybrid',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 70000,
                'salary_max' => 100000,
                'location' => 'Chattogram',
                'vacancy' => 2,
            ],
            [
                'title' => 'Frontend Developer',
                'description' => 'We are hiring a frontend developer to create modern, user-friendly interfaces with responsive design principles.',
                'requirements' => 'Experience with JavaScript, HTML, CSS, Bootstrap, and frontend performance optimization.',
                'responsibilities' => 'Implement UI components, improve accessibility, and work with backend developers to ship features.',
                'benefits' => 'Great team culture, modern hardware, and learning budget.',
                'job_type' => 'Full Time',
                'workplace' => 'Remote',
                'employment_status' => 'Full Time',
                'experience_level' => 'Junior',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 40000,
                'salary_max' => 65000,
                'location' => 'Dhaka',
                'vacancy' => 1,
            ],
            [
                'title' => 'Full Stack Developer',
                'description' => 'Take ownership of end-to-end product development for our internal tools and customer-facing applications.',
                'requirements' => 'Experience with Laravel, PHP, JavaScript, MySQL, Git, HTML, CSS, Bootstrap, and REST APIs.',
                'responsibilities' => 'Implement features across the stack and communicate with stakeholders to deliver business value.',
                'benefits' => 'Flexible schedule, remote support, and performance incentives.',
                'job_type' => 'Full Time',
                'workplace' => 'Hybrid',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 80000,
                'salary_max' => 120000,
                'location' => 'Rajshahi',
                'vacancy' => 2,
            ],
            [
                'title' => 'Software Engineer',
                'description' => 'Help us build and improve a scalable platform used by multiple teams across the organization.',
                'requirements' => 'Strong problem solving, software design, PHP, Laravel, MySQL, Git, and development discipline.',
                'responsibilities' => 'Collaborate with team members to deliver stable and maintainable software.',
                'benefits' => 'Health coverage, team events, and generous leave policy.',
                'job_type' => 'Full Time',
                'workplace' => 'Onsite',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 70000,
                'salary_max' => 95000,
                'location' => 'Khulna',
                'vacancy' => 1,
            ],
            [
                'title' => 'QA Engineer',
                'description' => 'We are hiring a QA engineer to ensure quality across web applications and software releases.',
                'requirements' => 'Experience in manual and automated testing with strong attention to detail and analytical thinking.',
                'responsibilities' => 'Test features, report bugs, and support release readiness.',
                'benefits' => 'Structured growth path, career mentorship, and team support.',
                'job_type' => 'Full Time',
                'workplace' => 'Remote',
                'employment_status' => 'Full Time',
                'experience_level' => 'Junior',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 35000,
                'salary_max' => 55000,
                'location' => 'Dhaka',
                'vacancy' => 1,
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'Help maintain our deployment pipelines and support a reliable cloud-based infrastructure.',
                'requirements' => 'Experience with CI/CD, cloud environments, Linux, and automation tools.',
                'responsibilities' => 'Manage deployments, monitor systems, and support team operations.',
                'benefits' => 'Flexible hours, remote work, and attractive compensation.',
                'job_type' => 'Full Time',
                'workplace' => 'Remote',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 80000,
                'salary_max' => 110000,
                'location' => 'Chattogram',
                'vacancy' => 1,
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'We are seeking a UI/UX designer who can craft thoughtful user experiences and engaging interfaces.',
                'requirements' => 'Experience with design systems, wireframes, prototyping, and user-centered design.',
                'responsibilities' => 'Design flows, create prototypes, and collaborate with engineering teams.',
                'benefits' => 'Creative team environment, learning support, and strong project variety.',
                'job_type' => 'Full Time',
                'workplace' => 'Hybrid',
                'employment_status' => 'Full Time',
                'experience_level' => 'Mid Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 50000,
                'salary_max' => 75000,
                'location' => 'Dhaka',
                'vacancy' => 1,
            ],
            [
                'title' => 'Junior Developer',
                'description' => 'An excellent opportunity for a junior developer to learn and grow while contributing to product delivery.',
                'requirements' => 'Basic knowledge of PHP, Laravel, JavaScript, HTML, CSS, and Git.',
                'responsibilities' => 'Support the team with feature development and bug fixing.',
                'benefits' => 'Mentorship, training budget, and attractive growth path.',
                'job_type' => 'Full Time',
                'workplace' => 'Onsite',
                'employment_status' => 'Full Time',
                'experience_level' => 'Entry Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 30000,
                'salary_max' => 45000,
                'location' => 'Sylhet',
                'vacancy' => 2,
            ],
            [
                'title' => 'Intern Software Engineer',
                'description' => 'Perfect for students or fresh graduates who want hands-on software engineering experience.',
                'requirements' => 'Knowledge of programming basics, willingness to learn, and strong problem solving skills.',
                'responsibilities' => 'Contribute to development tasks, assist QA, and learn from senior developers.',
                'benefits' => 'Internship certificate, guidance, and possible full-time conversion.',
                'job_type' => 'Internship',
                'workplace' => 'Remote',
                'employment_status' => 'Internship',
                'experience_level' => 'Entry Level',
                'education_level' => 'Bachelor',
                'salary_type' => 'Monthly',
                'salary_min' => 12000,
                'salary_max' => 18000,
                'location' => 'Dhaka',
                'vacancy' => 3,
            ],
        ];

        foreach ($companies as $index => $company) {
            $companyJobs = $jobTemplates;
            $startOffset = $index * 6;

            foreach ($companyJobs as $jobIndex => $jobData) {
                $position = $startOffset + $jobIndex;
                $template = $jobTemplates[$position % count($jobTemplates)];

                Job::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'title' => $template['title'],
                    ],
                    [
                        'slug' => Str::slug($template['title']) . '-' . $company->id,
                        'vacancy' => $template['vacancy'],
                        'job_type' => $template['job_type'],
                        'workplace' => $template['workplace'],
                        'employment_status' => $template['employment_status'],
                        'experience_level' => $template['experience_level'],
                        'education_level' => $template['education_level'],
                        'salary_type' => $template['salary_type'],
                        'salary_min' => $template['salary_min'],
                        'salary_max' => $template['salary_max'],
                        'location' => $template['location'],
                        'deadline' => now()->addDays(45 + ($company->id % 10)),
                        'description' => $template['description'],
                        'responsibilities' => $template['responsibilities'],
                        'requirements' => $template['requirements'],
                        'benefits' => $template['benefits'],
                        'status' => 'published',
                        'published_at' => now(),
                    ]
                );
            }
        }
    }
}
