<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'System Administrator',
                'is_active' => true,
            ],
            [
                'name' => 'Employer',
                'slug' => 'employer',
                'description' => 'Company Employer',
                'is_active' => true,
            ],
            [
                'name' => 'Job Seeker',
                'slug' => 'job-seeker',
                'description' => 'Job Seeker',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role,
            );
        }
    }
}
