<?php

namespace Database\Factories;

use App\Models\JobSeekerProfile;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        return [
            'job_seeker_profile_id' => JobSeekerProfile::factory(),
            'title' => $this->faker->sentence(3),
            'file_name' => $this->faker->word() . '.pdf',
            'file_path' => null,
            'file_type' => 'application/pdf',
            'file_size' => $this->faker->numberBetween(100000, 500000),
            'is_default' => true,
            'is_active' => true,
            'uploaded_at' => now(),
        ];
    }
}
