<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        if (! $jobSeekerRole) {
            $this->command->warn('Job seeker role not found. Skipping demo applications.');
            return;
        }

        $jobSeekers = User::where('role_id', $jobSeekerRole->id)->get();
        $publishedJobs = Job::query()->where('status', 'published')->get();

        if ($publishedJobs->isEmpty()) {
            $this->command->warn('No published jobs found. Skipping demo applications.');
            return;
        }

        $statuses = array_keys(JobApplication::statusOptions());
        $coverLetterTemplates = [
            'I am excited to apply for this position and believe my experience in Laravel, PHP, and full-stack development would help your team deliver reliable products.',
            'I would love the opportunity to bring my technical background and problem-solving skills to this role and contribute to your team.',
            'My experience in building scalable web applications and collaborating with product teams makes me a strong fit for this opportunity.',
        ];

        foreach ($jobSeekers as $jobSeeker) {
            $profile = $jobSeeker->jobSeekerProfile()->first();
            $defaultResume = $profile?->resumes()->where('is_default', true)->first() ?? $profile?->resumes()->first();

            if (! $defaultResume) {
                continue;
            }

            $jobCount = rand(2, 4);
            $selectedJobs = $publishedJobs->shuffle()->take($jobCount);

            foreach ($selectedJobs as $job) {
                $exists = JobApplication::where('job_id', $job->id)
                    ->where('user_id', $jobSeeker->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                JobApplication::create([
                    'job_id' => $job->id,
                    'user_id' => $jobSeeker->id,
                    'resume_id' => $defaultResume->id,
                    'cover_letter' => $coverLetterTemplates[array_rand($coverLetterTemplates)],
                    'status' => $statuses[array_rand($statuses)],
                    'applied_at' => now()->subDays(rand(1, 20)),
                ]);
            }
        }
    }
}
