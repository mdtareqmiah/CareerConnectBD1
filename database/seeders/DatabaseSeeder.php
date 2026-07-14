<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            SkillSeeder::class,
            DemoEmployerSeeder::class,
            DemoCompanySeeder::class,
            DemoJobSeekerSeeder::class,
            DemoProfileSeeder::class,
            DemoEducationSeeder::class,
            DemoExperienceSeeder::class,
            DemoJobSeekerSkillSeeder::class,
            DemoResumeSeeder::class,
            DemoResumeBuilderSeeder::class,
            DemoJobSeeder::class,
            DemoApplicationSeeder::class,
            DemoSavedJobSeeder::class,
            DemoNotificationSeeder::class,
            DemoInterviewInvitationSeeder::class,
            DemoEmailLogSeeder::class,
            DemoContactMessageSeeder::class,
            DemoFeedbackSeeder::class,
            DemoSupportTicketSeeder::class,
            DemoSupportTicketReplySeeder::class,
            DemoSystemSettingSeeder::class,
        ]);

        // Demo credentials:
        // Admin: admin@careerconnectbd.com / Admin12345
        // Employer: hr@abctech.com / password
        // Employer: hr@techsoft.com / password
        // Job Seeker: tareq@careerconnectbd.com / password
        // Job Seeker: asif@careerconnectbd.com / password
        // Job Seeker: sunia@careerconnectbd.com / password
        // Demo summary: 1 admin, 2 employers, 6 job seekers, 2 companies, 10-15 published jobs,
        // 20-30 applications, 20-30 saved jobs, 6 resumes, 6 resume builders, 50+ notifications,
        // 5-10 interview invitations, 10 contact messages, 15 feedback entries, 12 support tickets,
        // 30+ support replies, and system settings seeded.
    }
}
