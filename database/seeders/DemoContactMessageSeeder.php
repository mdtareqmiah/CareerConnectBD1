<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@careerconnectbd.com')->first()
            ?? User::whereHas('role', fn ($query) => $query->where('slug', 'admin'))->first();

        $names = [
            'Farhan Rahman', 'Nusrat Jahan', 'Ibrahim Hossain', 'Sadia Akter', 'Tamim Hasan',
            'Mitu Rahman', 'Rafiq Ahmed', 'Anika Binte', 'Shahriar Kabir', 'Mahin Islam',
        ];

        $subjects = [
            'Need help with resume upload',
            'Question about application tracking',
            'Can I change my email address?',
            'Interest in partnership opportunities',
            'Report an issue with notifications',
            'Welcome message and onboarding',
            'Concern about company verification',
            'How to update my profile photo?',
            'Question about interview scheduling',
            'Need support for account recovery',
        ];

        $messages = [
            'I am having trouble uploading my latest resume from my phone. Could you please guide me?',
            'I can see my application list but the status does not refresh after an update.',
            'My old email is no longer active. I would like to update it for future communications.',
            'We are planning to recruit through the platform and would like to know about partnership options.',
            'I am not receiving notifications when a new status update arrives.',
            'The onboarding experience is good, but I would appreciate a bit more guidance for first-time users.',
            'My company is waiting for verification and I need an update on the review process.',
            'I uploaded a new profile photo but it still shows the old one on the dashboard.',
            'Can you confirm how interview invitations are sent and whether I can reschedule?',
            'I lost access to my account and need a recovery option or support contact.',
        ];

        $statuses = ContactMessage::STATUSES;
        $categories = ContactMessage::CATEGORIES;

        for ($i = 0; $i < 10; $i++) {
            $status = $statuses[array_rand($statuses)];
            $subject = $subjects[$i];
            $message = $messages[$i];
            $email = strtolower(str_replace(' ', '.', $names[$i])) . '@gmail.com';

            ContactMessage::firstOrCreate(
                [
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                ],
                [
                    'full_name' => $names[$i],
                    'phone' => '01' . rand(3, 9) . rand(10000000, 99999999),
                    'category' => $categories[array_rand($categories)],
                    'assigned_admin_id' => $admin?->id,
                    'status' => $status,
                    'read_at' => in_array($status, ['in_review', 'replied', 'closed']) ? now()->subDays(rand(1, 6)) : null,
                    'replied_at' => in_array($status, ['replied', 'closed']) ? now()->subDays(rand(1, 3)) : null,
                    'closed_at' => $status === 'closed' ? now()->subDays(rand(1, 2)) : null,
                ]
            );
        }
    }
}
