<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $title = $this->faker->jobTitle();
        $salaryMin = $this->faker->numberBetween(20000, 50000);
        $salaryMax = $this->faker->numberBetween($salaryMin + 5000, $salaryMin + 30000);

        return [
            'company_id' => Company::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(6),
            'vacancy' => $this->faker->numberBetween(1, 10),
            'job_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Internship']),
            'workplace' => $this->faker->randomElement(['On-site', 'Remote', 'Hybrid']),
            'employment_status' => $this->faker->randomElement(['Permanent', 'Temporary']),
            'experience_level' => $this->faker->randomElement(['Entry Level', 'Mid Level', 'Senior Level']),
            'education_level' => $this->faker->randomElement(['High School', 'Diploma', 'Bachelor', 'Master', 'Doctorate']),
            'salary_type' => $this->faker->randomElement(['Monthly', 'Yearly']),
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'location' => $this->faker->city(),
            'deadline' => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'description' => $this->faker->paragraphs(3, true),
            'responsibilities' => $this->faker->paragraphs(2, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'benefits' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }
}
