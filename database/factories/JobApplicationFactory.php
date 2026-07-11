<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'user_id' => User::factory(),
            'resume_id' => Resume::factory(),
            'cover_letter' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted']),
            'applied_at' => now(),
        ];
    }
}
