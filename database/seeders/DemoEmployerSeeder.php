<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoEmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employerRole = Role::where('slug', 'employer')->first();

        if (! $employerRole) {
            $this->command->warn('Employer role not found. Skipping demo employers.');
            return;
        }

        $adminUser = User::where('email', 'admin@careerconnectbd.com')->first();

        $employers = [
            [
                'name' => 'ABC HR Manager',
                'email' => 'hr@abctech.com',
                'password' => 'password',
            ],
            [
                'name' => 'TechSoft HR',
                'email' => 'hr@techsoft.com',
                'password' => 'password',
            ],
        ];

        foreach ($employers as $employer) {
            $user = User::updateOrCreate(
                ['email' => $employer['email']],
                [
                    'name' => $employer['name'],
                    'password' => Hash::make($employer['password']),
                    'role_id' => $employerRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $user->forceFill(['company_id' => null])->save();

            if ($adminUser) {
                $user->forceFill(['last_login_at' => null])->save();
            }
        }
    }
}
