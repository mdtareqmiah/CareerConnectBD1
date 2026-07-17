<?php

namespace Database\Seeders;

use App\Models\EmailLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoEmailLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();
        $employerRole = Role::where('slug', 'employer')->first();

        $jobSeekers = $jobSeekerRole ? User::where('role_id', $jobSeekerRole->id)->get() : collect();
        $employers = $employerRole ? User::where('role_id', $employerRole->id)->get() : collect();
        $admin = User::where('email', 'admin@careerconnectbd.com')->first();

        $templates = [
            [
                'recipient' => 'job-seeker',
                'subject' => 'Application Confirmation',
                'type' => 'application_confirmation',
                'status' => 'sent',
            ],
            [
                'recipient' => 'job-seeker',
                'subject' => 'Interview Invitation',
                'type' => 'interview_invitation',
                'status' => 'sent',
            ],
            [
                'recipient' => 'employer',
                'subject' => 'Application Review Reminder',
                'type' => 'application_review',
                'status' => 'queued',
            ],
            [
                'recipient' => 'job-seeker',
                'subject' => 'Welcome Email',
                'type' => 'welcome',
                'status' => 'sent',
            ],
            [
                'recipient' => 'employer',
                'subject' => 'New Candidate Alert',
                'type' => 'candidate_alert',
                'status' => 'failed',
            ],
        ];

        foreach ($jobSeekers as $user) {
            foreach (array_slice($templates, 0, 2) as $template) {
                EmailLog::firstOrCreate(
                    [
                        'recipient' => $user->email,
                        'subject' => $template['subject'],
                        'type' => $template['type'],
                    ],
                    [
                        'status' => $template['status'],
                        'sent_at' => now()->subHours(rand(1, 24)),
                        'failure_message' => $template['status'] === 'failed' ? 'Temporary delivery issue' : null,
                        'triggered_by' => $admin?->id,
                    ]
                );
            }
        }

        foreach ($employers as $user) {
            foreach (array_slice($templates, 2, 2) as $template) {
                EmailLog::firstOrCreate(
                    [
                        'recipient' => $user->email,
                        'subject' => $template['subject'],
                        'type' => $template['type'],
                    ],
                    [
                        'status' => $template['status'],
                        'sent_at' => now()->subHours(rand(1, 24)),
                        'failure_message' => $template['status'] === 'failed' ? 'Temporary delivery issue' : null,
                        'triggered_by' => $admin?->id,
                    ]
                );
            }
        }
    }
}
