<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id' => \App\Models\User::factory(),
            'company_name' => $this->faker->company(),
            'company_logo' => null,
            'industry' => $this->faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Education', 'Retail', 'Manufacturing', 'Hospitality']),
            'company_size' => $this->faker->randomElement(['1-50', '51-200', '201-500', '501-1000', '1000+']),
            'founded_year' => $this->faker->year(),
            'website' => $this->faker->url(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'company_description' => $this->faker->paragraph(),
        ];
    }
}
