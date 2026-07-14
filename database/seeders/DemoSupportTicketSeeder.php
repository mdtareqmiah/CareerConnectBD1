<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@careerconnectbd.com')->first()
            ?? User::whereHas('role', fn ($query) => $query->where('slug', 'admin'))->first();

        $users = User::whereHas('role', fn ($query) => $query->whereIn('slug', ['job-seeker', 'employer']))->get();

        if ($users->isEmpty()) {
            return;
        }

        $categories = [
            'resume_upload',
            'application_issue',
            'password_problem',
            'interview_issue',
            'company_verification',
            'notification_issue',
        ];

        $subjects = [
            'Unable to upload resume',
            'Application status not updating',
            'Password reset link not working',
            'Interview invitation arrived late',
            'Company verification pending for too long',
            'Notifications not appearing',
            'Need help with account access',
            'Job application submission failed',
            'Cannot update profile photo',
            'Interview date needs to be changed',
            'Could not verify company information',
            'Notifications are delayed after application updates',
        ];

        $messages = [
            'I tried uploading my CV several times but the platform keeps rejecting the file.',
            'My application status still shows pending even after the employer reviewed it.',
            'I clicked the password reset link but it failed to open the form.',
            'I received an interview invitation but it did not include the correct time slot.',
            'My company profile has been under review for several days and I need an update.',
            'I am not receiving new notifications when my application changes status.',
            'I cannot access my account even though my login details are correct.',
            'The application submission button does not respond on the browser I am using.',
            'The upload button for profile photo freezes after choosing a file.',
            'The employer asked for a different interview time and I need to coordinate that.',
            'My company details are correct but the verification check still marks them as incomplete.',
            'The notifications panel is loading slowly and sometimes misses updates.',
        ];

        $statuses = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;

        for ($i = 0; $i < 12; $i++) {
            $status = $statuses[array_rand($statuses)];
            $ticketNumber = 'TKT-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT);
            $user = $users->random();

            SupportTicket::firstOrCreate(
                [
                    'ticket_number' => $ticketNumber,
                ],
                [
                    'user_id' => $user->id,
                    'category' => $categories[array_rand($categories)],
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => $status,
                    'subject' => $subjects[$i],
                    'message' => $messages[$i],
                    'assigned_admin_id' => $admin?->id,
                    'closed_at' => $status === 'closed' ? now()->subDays(rand(1, 2)) : null,
                    'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(rand(1, 3)) : null,
                    'last_reply_at' => now()->subHours(rand(1, 12)),
                ]
            );
        }
    }
}
