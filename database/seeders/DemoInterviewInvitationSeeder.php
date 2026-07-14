<?php

namespace Database\Seeders;

use App\Models\InterviewInvitation;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class DemoInterviewInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = JobApplication::whereIn('status', ['interview', 'shortlisted', 'reviewed', 'accepted'])->get();

        foreach ($applications as $application) {
            $job = $application->job;
            $candidate = $application->user;
            $employer = $job?->company?->employer;

            if (! $job || ! $candidate || ! $employer) {
                continue;
            }

            InterviewInvitation::updateOrCreate(
                [
                    'candidate_id' => $candidate->id,
                    'job_id' => $job->id,
                    'employer_id' => $employer->id,
                ],
                [
                    'subject' => 'Interview Invitation for ' . $job->title,
                    'interview_at' => now()->addDays(rand(1, 7))->setTime(rand(10, 16), rand(0, 59)),
                    'meeting_link' => rand(0, 1) ? 'https://meet.google.com/demo-' . $job->id : null,
                    'location' => rand(0, 1) ? null : 'Conference Room A, ' . $job->location,
                    'notes' => 'Please join the interview session with your resume and portfolio ready.',
                    'status' => InterviewInvitation::statuses()[array_rand(InterviewInvitation::statuses())],
                    'responded_at' => now()->subHours(rand(1, 6)),
                ]
            );
        }
    }
}
