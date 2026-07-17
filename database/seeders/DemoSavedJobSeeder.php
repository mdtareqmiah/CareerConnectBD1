<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Role;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSavedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        if (! $jobSeekerRole) {
            $this->command->warn('Job seeker role not found. Skipping saved jobs.');
            return;
        }

        $jobSeekers = User::where('role_id', $jobSeekerRole->id)->get();
        $publishedJobs = Job::query()->where('status', 'published')->get();

        if ($publishedJobs->isEmpty()) {
            $this->command->warn('No published jobs found. Skipping saved jobs.');
            return;
        }

        foreach ($jobSeekers as $jobSeeker) {
            $savedJobs = $publishedJobs->shuffle()->take(rand(3, 5));

            foreach ($savedJobs as $job) {
                SavedJob::firstOrCreate([
                    'user_id' => $jobSeeker->id,
                    'job_id' => $job->id,
                ]);
            }
        }
    }
}
