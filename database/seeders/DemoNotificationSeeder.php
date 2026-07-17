<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notificationService = app(NotificationService::class);
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();
        $employerRole = Role::where('slug', 'employer')->first();
        $adminRole = Role::where('slug', 'admin')->first();

        $jobSeekers = $jobSeekerRole ? User::where('role_id', $jobSeekerRole->id)->get() : collect();
        $employers = $employerRole ? User::where('role_id', $employerRole->id)->get() : collect();
        $admins = $adminRole ? User::where('role_id', $adminRole->id)->get() : collect();

        $jobSeekerTemplates = [
            ['title' => 'Welcome', 'message' => 'Welcome to CareerConnectBD! Start building your profile and applying for opportunities.', 'link' => '/job-seeker/dashboard'],
            ['title' => 'Application Submitted', 'message' => 'Your application has been submitted successfully. We will review it shortly.', 'link' => '/job-seeker/applications'],
            ['title' => 'Application Reviewed', 'message' => 'Your application has been reviewed by the employer and is now in progress.', 'link' => '/job-seeker/applications'],
            ['title' => 'Interview Invitation', 'message' => 'You have received an interview invitation for one of your recent applications.', 'link' => '/job-seeker/interviews'],
            ['title' => 'Job Recommendation', 'message' => 'We found a few jobs that match your profile and skills.', 'link' => '/jobs'],
            ['title' => 'Resume Updated', 'message' => 'Your resume has been updated and is ready for new applications.', 'link' => '/job-seeker/resume'],
            ['title' => 'Profile Completed', 'message' => 'Your profile is now complete and more visible to employers.', 'link' => '/job-seeker/profile'],
        ];

        $employerTemplates = [
            ['title' => 'New Applicant', 'message' => 'A new candidate has applied to one of your jobs. Review the profile and resume.', 'link' => '/employer/applications'],
            ['title' => 'Interview Scheduled', 'message' => 'An interview invitation has been sent for one of your recent openings.', 'link' => '/employer/interviews'],
            ['title' => 'Company Verified', 'message' => 'Your company profile has been verified and is now active.', 'link' => '/employer/company'],
            ['title' => 'Profile Strength', 'message' => 'Your recruiting dashboard is now ready with updated insights.', 'link' => '/employer/dashboard'],
        ];

        $adminTemplates = [
            ['title' => 'System Update', 'message' => 'A new system update is available for review.', 'link' => '/admin/dashboard'],
            ['title' => 'Platform Activity', 'message' => 'There is recent platform activity that needs monitoring.', 'link' => '/admin/dashboard'],
        ];

        foreach ($jobSeekers as $user) {
            $templates = $jobSeekerTemplates;
            shuffle($templates);
            $selected = array_slice($templates, 0, rand(5, 8));

            foreach ($selected as $index => $template) {
                $notificationService->notifySystem($user, [
                    'title' => $template['title'],
                    'message' => $template['message'],
                    'link' => $template['link'],
                    'created_at' => now()->subDays(rand(0, 10))->toIso8601String(),
                ]);
            }
        }

        foreach ($employers as $user) {
            $templates = $employerTemplates;
            shuffle($templates);
            $selected = array_slice($templates, 0, rand(3, 5));

            foreach ($selected as $template) {
                $notificationService->notifySystem($user, [
                    'title' => $template['title'],
                    'message' => $template['message'],
                    'link' => $template['link'],
                    'created_at' => now()->subDays(rand(0, 10))->toIso8601String(),
                ]);
            }
        }

        foreach ($admins as $user) {
            $templates = $adminTemplates;
            shuffle($templates);
            $selected = array_slice($templates, 0, 2);

            foreach ($selected as $template) {
                $notificationService->notifySystem($user, [
                    'title' => $template['title'],
                    'message' => $template['message'],
                    'link' => $template['link'],
                    'created_at' => now()->subDays(rand(0, 10))->toIso8601String(),
                ]);
            }
        }
    }
}
