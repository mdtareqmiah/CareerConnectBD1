<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoJobSeekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        if (! $jobSeekerRole) {
            $this->command->warn('Job seeker role not found. Skipping demo job seekers.');
            return;
        }

        $jobSeekers = [
            [
                'name' => 'Md Tareq Miah',
                'email' => 'tareq@careerconnectbd.com',
            ],
            [
                'name' => 'Asif Ahmed',
                'email' => 'asif@careerconnectbd.com',
            ],
            [
                'name' => 'Sunia Akter',
                'email' => 'sunia@careerconnectbd.com',
            ],
            [
                'name' => 'Rahim Uddin',
                'email' => 'rahim@careerconnectbd.com',
            ],
            [
                'name' => 'Karim Hasan',
                'email' => 'karim@careerconnectbd.com',
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat@careerconnectbd.com',
            ],
        ];

        foreach ($jobSeekers as $jobSeeker) {
            User::updateOrCreate(
                ['email' => $jobSeeker['email']],
                [
                    'name' => $jobSeeker['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $jobSeekerRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
