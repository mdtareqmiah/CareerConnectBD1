<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoFeedbackSeeder extends Seeder
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

        $subjects = [
            'Great experience overall',
            'Resume builder is very helpful',
            'Need more filtering options',
            'Application tracking needs improvement',
            'Notifications could be more instant',
            'Loved the simple dashboard',
            'Could improve company verification speed',
            'The job search feels relevant',
            'Would like more interview tips',
            'Mobile experience is smooth',
            'Profile completion progress is motivating',
            'Thanks for the support team',
            'Job alerts are useful',
            'Need better guidance for first-time users',
            'Excellent platform for job seekers',
        ];

        $messages = [
            'The platform feels easy to use and the UI is clean. I had a good experience applying for jobs.',
            'The resume builder helped me create a professional resume within a few minutes.',
            'It would be helpful to filter jobs by salary range and remote options for easier browsing.',
            'The application tracking page could be more detailed and informative for applicants.',
            'I would like to receive notifications immediately after each employer update.',
            'The dashboard layout is simple and clear, which makes the whole process less stressful.',
            'Company verification is important, but the review time feels a bit slow for urgent requests.',
            'The job recommendations are usually relevant to my background and experience.',
            'A few interview tips and preparation resources would make the experience even better.',
            'The mobile experience is smooth and I could access most sections without issues.',
            'The profile completion tracker encourages me to add missing section details.',
            'Support responses have been helpful and professional whenever I needed assistance.',
            'Job alerts are a useful feature and help me stay updated on new openings.',
            'A quick onboarding guide for first-time users would reduce confusion at the start.',
            'Overall, this is a solid platform for connecting recruiters and job seekers efficiently.',
        ];

        $types = Feedback::TYPES;
        $statuses = Feedback::STATUSES;
        $ratings = [5, 4, 3];

        for ($i = 0; $i < 15; $i++) {
            $status = $statuses[array_rand($statuses)];
            $user = $users->random();

            Feedback::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'subject' => $subjects[$i],
                    'message' => $messages[$i],
                ],
                [
                    'type' => $types[array_rand($types)],
                    'rating' => $ratings[array_rand($ratings)],
                    'status' => $status,
                    'assigned_admin_id' => $admin?->id,
                    'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(rand(1, 4)) : null,
                    'closed_at' => $status === 'closed' ? now()->subDays(rand(1, 2)) : null,
                ]
            );
        }
    }
}
