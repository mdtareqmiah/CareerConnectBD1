<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'System Administrator',
                'is_active' => true,
            ]
        );

        $employerRole = Role::where('slug', 'employer')->first();
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        $defaultAdmin = User::updateOrCreate(
            ['email' => 'admin@careerconnectbd.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin12345'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::query()
            ->where('id', '!=', $defaultAdmin->id)
            ->where('role_id', $adminRole->id)
            ->get()
            ->each(function (User $user) use ($employerRole, $jobSeekerRole): void {
                if ($user->company && $employerRole) {
                    $user->forceFill(['role_id' => $employerRole->id])->save();
                    return;
                }

                if ($user->jobSeekerProfile && $jobSeekerRole) {
                    $user->forceFill(['role_id' => $jobSeekerRole->id])->save();
                    return;
                }

                $user->forceFill(['role_id' => null])->save();
            });
    }
}
