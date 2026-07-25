<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Feedback;
use App\Models\InterviewInvitation;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function createUserWithRole(string $slug, array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role_id' => $this->createRole($slug, ucfirst(str_replace('-', ' ', $slug)))->id,
            'is_active' => true,
        ], $attributes));
    }

    private function createJobSeekerWithResume(string $name = 'Job Seeker'): array
    {
        $user = $this->createUserWithRole('job-seeker', ['name' => $name]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'is_active' => true,
        ]);

        return [$user, $profile, $resume];
    }

    private function createEmployerWithCompany(string $name = 'Employer'): array
    {
        $user = $this->createUserWithRole('employer', ['name' => $name]);
        $company = Company::factory()->create(['employer_id' => $user->id]);

        return [$user, $company];
    }

    private function createPublishedJobForEmployer(User $employer, Company $company, string $title = 'Senior PHP Developer'): Job
    {
        return Job::factory()->create([
            'company_id' => $company->id,
            'title' => $title,
            'status' => 'published',
            'deadline' => now()->addWeeks(2),
            'published_at' => now(),
        ]);
    }

    public function test_employer_receives_notification_after_successful_job_application(): void
    {
        config(['broadcasting.default' => 'null']);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $this->actingAs($jobSeeker)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ])->assertRedirect(route('jobs.show', $job));

        $application = JobApplication::query()->latest('id')->first();
        $notification = $employer->fresh()->unreadNotifications()->first();

        $this->assertNotNull($application);
        $this->assertNotNull($notification);
        $this->assertSame('New application received', $notification->data['title']);
        $this->assertSame('New application received for Backend Engineer.', $notification->data['message']);
        $this->assertSame(route('employer.applications.show', $application), $notification->data['link']);
        $this->assertSame($application->id, $notification->data['application_id']);
        $this->assertSame($job->id, $notification->data['job_id']);
        $this->assertSame('Backend Engineer', $notification->data['job_title']);
        $this->assertSame($jobSeeker->id, $notification->data['applicant_id']);
        $this->assertSame('Amina Khan', $notification->data['applicant_name']);
    }

    public function test_duplicate_job_application_does_not_create_duplicate_notification(): void
    {
        config(['broadcasting.default' => 'null']);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $payload = [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ];

        $this->actingAs($jobSeeker)->post(route('job-applications.store'), $payload)->assertRedirect(route('jobs.show', $job));
        $this->actingAs($jobSeeker)->post(route('job-applications.store'), $payload)->assertRedirect(route('jobs.show', $job));

        $this->assertSame(1, $employer->fresh()->unreadNotifications()->count());
    }

    public function test_job_seeker_receives_notification_after_application_status_changes(): void
    {
        config(['broadcasting.default' => 'null']);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $this->actingAs($jobSeeker)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ]);

        $application = JobApplication::query()->latest('id')->firstOrFail();

        $this->actingAs($employer)->patch(route('employer.applications.update_status', $application), [
            'status' => 'reviewed',
        ])->assertRedirect(route('employer.applications.show', $application));

        $notification = $jobSeeker->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Application status updated', $notification->data['title']);
        $this->assertSame('Your application for Backend Engineer has been updated to Reviewed.', $notification->data['message']);
        $this->assertSame(route('job-seeker.applications.show', $application), $notification->data['link']);

        $this->actingAs($employer)->patch(route('employer.applications.update_status', $application), [
            'status' => 'reviewed',
        ])->assertRedirect(route('employer.applications.show', $application));

        $this->assertSame(1, $jobSeeker->fresh()->unreadNotifications()->count());
    }

    public function test_job_seeker_receives_interview_invitation_notification(): void
    {
        config(['broadcasting.default' => 'null']);
        Mail::fake();

        [$candidate, , ] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $this->actingAs($employer)->post(route('employer.interview-invitations.store'), [
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'subject' => 'Interview for Backend Engineer',
            'interview_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'meeting_link' => 'https://meet.example.com/interview',
            'location' => 'Online',
            'notes' => 'Please join on time.',
        ])->assertRedirect(route('employer.interview-invitations.index'));

        $invitation = InterviewInvitation::query()->latest('id')->firstOrFail();
        $notification = $candidate->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Interview invitation', $notification->data['title']);
        $this->assertSame('You received a new interview invitation for Backend Engineer', $notification->data['message']);
        $this->assertSame(route('job-seeker.interview-invitations.show', $invitation), $notification->data['link']);
        $this->assertSame($invitation->id, $notification->data['interview_invitation_id']);
        $this->assertSame($job->id, $notification->data['job_id']);
        $this->assertSame('Backend Engineer', $notification->data['job_title']);
    }

    public function test_employer_receives_interview_response_notification(): void
    {
        config(['broadcasting.default' => 'null']);
        Mail::fake();

        [$candidate, , ] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $this->actingAs($employer)->post(route('employer.interview-invitations.store'), [
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'subject' => 'Interview for Backend Engineer',
            'interview_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'meeting_link' => 'https://meet.example.com/interview',
            'location' => 'Online',
            'notes' => 'Please join on time.',
        ]);

        $invitation = InterviewInvitation::query()->latest('id')->firstOrFail();

        $this->actingAs($candidate)->patch(route('job-seeker.interview-invitations.respond', $invitation), [
            'response' => 'accepted',
        ])->assertRedirect(route('job-seeker.interview-invitations.index'));

        $notification = $employer->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Interview response received', $notification->data['title']);
        $this->assertSame('Amina Khan accepted the interview invitation.', $notification->data['message']);
        $this->assertSame(route('employer.interview-invitations.show', $invitation), $notification->data['link']);
        $this->assertSame('accepted', $notification->data['response']);
        $this->assertSame('Amina Khan', $notification->data['candidate_name']);
    }

    public function test_admin_receives_notification_after_support_ticket_creation(): void
    {
        config(['broadcasting.default' => 'null']);

        $activeAdmin = $this->createUserWithRole('admin', ['name' => 'Active Admin']);
        $inactiveAdmin = $this->createUserWithRole('admin', ['name' => 'Inactive Admin', 'is_active' => false]);
        $user = $this->createUserWithRole('job-seeker', ['name' => 'Support User']);

        $this->actingAs($user)->post(route('support-tickets.store'), [
            'category' => 'Account',
            'priority' => 'high',
            'subject' => 'Login problem',
            'message' => str_repeat('I cannot access my account. ', 4),
        ])->assertRedirect();

        $activeNotification = $activeAdmin->fresh()->unreadNotifications()->first();

        $this->assertNotNull($activeNotification);
        $this->assertSame('New support ticket received', $activeNotification->data['title']);
        $this->assertSame('New support ticket received: Login problem.', $activeNotification->data['message']);
        $this->assertSame(0, $inactiveAdmin->fresh()->unreadNotifications()->count());
    }

    public function test_ticket_owner_receives_notification_after_admin_reply(): void
    {
        config(['broadcasting.default' => 'null']);
        Mail::fake();

        $owner = $this->createUserWithRole('job-seeker', ['name' => 'Support User']);
        $admin = $this->createUserWithRole('admin', ['name' => 'Admin']);

        $this->actingAs($owner)->post(route('support-tickets.store'), [
            'category' => 'Account',
            'priority' => 'medium',
            'subject' => 'Login problem',
            'message' => str_repeat('I cannot access my account. ', 4),
        ]);

        $ticket = SupportTicket::query()->latest('id')->firstOrFail();

        $this->actingAs($admin)->post(route('support-tickets.replies.store', $ticket), [
            'message' => 'Please reset your password and try again.',
        ])->assertRedirect();

        $notification = $owner->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Admin replied to your support ticket', $notification->data['title']);
        $this->assertSame('Admin replied to your support ticket: Login problem.', $notification->data['message']);
        $this->assertSame(route('support-tickets.show', $ticket), $notification->data['link']);
    }

    public function test_ticket_owner_receives_notification_after_ticket_status_changes(): void
    {
        config(['broadcasting.default' => 'null']);

        $owner = $this->createUserWithRole('job-seeker', ['name' => 'Support User']);
        $admin = $this->createUserWithRole('admin', ['name' => 'Admin']);

        $this->actingAs($owner)->post(route('support-tickets.store'), [
            'category' => 'Account',
            'priority' => 'medium',
            'subject' => 'Login problem',
            'message' => str_repeat('I cannot access my account. ', 4),
        ]);

        $ticket = SupportTicket::query()->latest('id')->firstOrFail();

        $this->actingAs($admin)->patch(route('admin.support-tickets.status', $ticket), [
            'status' => 'resolved',
        ])->assertRedirect();

        $notification = $owner->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Support ticket status updated', $notification->data['title']);
        $this->assertSame("Your support ticket 'Login problem' is now resolved.", $notification->data['message']);

        $this->actingAs($admin)->patch(route('admin.support-tickets.status', $ticket), [
            'status' => 'resolved',
        ])->assertRedirect();

        $this->assertSame(1, $owner->fresh()->unreadNotifications()->count());
    }

    public function test_admin_receives_notification_after_feedback_submission(): void
    {
        config(['broadcasting.default' => 'null']);

        $activeAdmin = $this->createUserWithRole('admin', ['name' => 'Active Admin']);
        $inactiveAdmin = $this->createUserWithRole('admin', ['name' => 'Inactive Admin', 'is_active' => false]);
        $user = $this->createUserWithRole('employer', ['name' => 'Feedback User']);

        $this->actingAs($user)->post(route('feedback.store'), [
            'type' => 'general_feedback',
            'subject' => 'Great platform',
            'message' => str_repeat('The platform is working well and I have a suggestion. ', 3),
            'rating' => 5,
        ])->assertRedirect();

        $notification = $activeAdmin->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('New feedback received', $notification->data['title']);
        $this->assertSame('New feedback received from Feedback User.', $notification->data['message']);
        $this->assertSame(0, $inactiveAdmin->fresh()->unreadNotifications()->count());
    }

    public function test_job_seeker_cannot_access_another_users_notifications(): void
    {
        config(['broadcasting.default' => 'null']);

        $firstUser = $this->createUserWithRole('job-seeker', ['name' => 'First User']);
        $secondUser = $this->createUserWithRole('job-seeker', ['name' => 'Second User']);

        $notification = $secondUser->notifications()->create([
            'id' => 'second-user-notification',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Private', 'message' => 'Private message', 'link' => '#'],
        ]);

        $this->actingAs($firstUser)->patch(route('notifications.read', $notification))->assertForbidden();
    }

    public function test_employer_cannot_access_another_employers_notifications(): void
    {
        config(['broadcasting.default' => 'null']);

        $firstUser = $this->createUserWithRole('employer', ['name' => 'First Employer']);
        $secondUser = $this->createUserWithRole('employer', ['name' => 'Second Employer']);

        $notification = $secondUser->notifications()->create([
            'id' => 'second-employer-notification',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Private', 'message' => 'Private message', 'link' => '#'],
        ]);

        $this->actingAs($firstUser)->patch(route('notifications.read', $notification))->assertForbidden();
    }

    public function test_notification_unread_count_is_correct(): void
    {
        config(['broadcasting.default' => 'null']);

        $user = $this->createUserWithRole('job-seeker', ['name' => 'Count User']);
        $service = app(NotificationService::class);

        $user->notifications()->create([
            'id' => 'notif-1',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'One', 'message' => 'First message', 'link' => '#'],
        ]);

        $second = $user->notifications()->create([
            'id' => 'notif-2',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Two', 'message' => 'Second message', 'link' => '#'],
        ]);

        $this->assertSame(2, $service->unreadCount($user));

        $service->markAsRead($second);

        $this->assertSame(1, $service->unreadCount($user));
    }

    public function test_job_application_succeeds_when_realtime_broadcasting_is_disabled(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.realtime_enabled' => false]);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $response = $this->actingAs($jobSeeker)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $this->assertSame(1, $employer->fresh()->unreadNotifications()->count());
    }

    public function test_application_status_update_succeeds_when_realtime_broadcasting_is_disabled(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.realtime_enabled' => false]);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $this->actingAs($jobSeeker)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ]);

        $application = JobApplication::query()->latest('id')->firstOrFail();

        $response = $this->actingAs($employer)->patch(route('employer.applications.update_status', $application), [
            'status' => 'reviewed',
        ]);

        $response->assertRedirect(route('employer.applications.show', $application));
        $this->assertSame(1, $jobSeeker->fresh()->unreadNotifications()->count());
    }

    public function test_mark_actions_succeed_when_realtime_broadcasting_is_disabled(): void
    {
        config(['broadcasting.default' => 'reverb', 'broadcasting.realtime_enabled' => false]);

        $user = $this->createUserWithRole('job-seeker', ['name' => 'Count User']);

        $first = $user->notifications()->create([
            'id' => 'notif-1',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'One', 'message' => 'First message', 'link' => '#'],
        ]);

        $second = $user->notifications()->create([
            'id' => 'notif-2',
            'type' => 'App\\Notifications\\SystemNotification',
            'data' => ['title' => 'Two', 'message' => 'Second message', 'link' => '#'],
        ]);

        $this->actingAs($user)->patch(route('notifications.read', $first))->assertRedirect();
        $this->actingAs($user)->patch(route('notifications.read-all'))->assertRedirect();

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_job_application_still_succeeds_when_realtime_broadcaster_is_unreachable(): void
    {
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.realtime_enabled' => true,
            'broadcasting.connections.reverb.options.host' => '127.0.0.1',
            'broadcasting.connections.reverb.options.port' => 8080,
            'broadcasting.connections.reverb.options.scheme' => 'http',
            'broadcasting.connections.reverb.options.useTLS' => false,
        ]);

        [$jobSeeker, , $resume] = $this->createJobSeekerWithResume('Amina Khan');
        [$employer, $company] = $this->createEmployerWithCompany('Hiring Team');
        $job = $this->createPublishedJobForEmployer($employer, $company, 'Backend Engineer');

        $response = $this->actingAs($jobSeeker)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('I am a strong fit for this role. ', 4),
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $this->assertSame(1, $employer->fresh()->unreadNotifications()->count());
    }
}
