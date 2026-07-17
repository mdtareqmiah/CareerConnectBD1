<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\Resume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = JobSeekerProfile::with(['educations', 'experiences', 'skills'])->get();

        foreach ($profiles as $profile) {
            $resume = Resume::updateOrCreate(
                [
                    'job_seeker_profile_id' => $profile->id,
                    'title' => 'Professional Resume',
                ],
                [
                    'file_name' => 'resume.pdf',
                    'file_path' => 'resumes/resume.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 250000,
                    'is_default' => true,
                    'is_active' => true,
                    'uploaded_at' => now()->subDays(rand(5, 30)),
                ]
            );

            if (! $resume->wasRecentlyCreated && $resume->file_path === null) {
                $resume->forceFill([
                    'file_path' => 'resumes/resume.pdf',
                    'file_name' => 'resume.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 250000,
                    'uploaded_at' => now()->subDays(rand(5, 30)),
                ])->save();
            }
        }
    }
}
