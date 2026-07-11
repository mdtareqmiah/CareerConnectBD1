<?php

namespace Database\Factories;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobSeekerProfileFactory extends Factory
{
    protected $model = JobSeekerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'nationality' => $this->faker->country(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'professional_title' => $this->faker->jobTitle(),
            'professional_summary' => $this->faker->paragraph(),
            'current_job_title' => $this->faker->jobTitle(),
            'current_company' => $this->faker->company(),
            'years_of_experience' => $this->faker->numberBetween(0, 20),
            'expected_salary' => $this->faker->numberBetween(20000, 100000),
            'preferred_job_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Internship']),
            'preferred_workplace' => $this->faker->randomElement(['Remote', 'On-site', 'Hybrid']),
            'preferred_location' => $this->faker->city(),
            'linkedin_url' => $this->faker->url(),
            'github_url' => $this->faker->url(),
            'portfolio_url' => $this->faker->url(),
            'website_url' => $this->faker->url(),
            'profile_photo' => null,
            'is_profile_completed' => true,
            'is_available_for_work' => true,
        ];
    }
}
