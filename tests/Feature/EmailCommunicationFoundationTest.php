<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\EmailLog;
use App\Models\InterviewInvitation;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailCommunicationFoundationTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    public function test_admin_can_view_email_logs(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->createRole('admin', 'Admin')->id,
            'is_active' => true,
        ]);

        EmailLog::create([
            'recipient' => 'candidate@example.com',
            'subject' => 'Interview invitation',
            'type' => 'interview_invitation',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->actingAs($admin)->get(route('admin.email-logs.index'))->assertOk();
    }

    public function test_job_seeker_can_view_and_respond_to_invitation(): void
    {
        $candidate = User::factory()->create([
            'role_id' => $this->createRole('job-seeker', 'Job Seeker')->id,
            'is_active' => true,
        ]);
        $employer = User::factory()->create([
            'role_id' => $this->createRole('employer', 'Employer')->id,
            'is_active' => true,
        ]);

        $company = Company::factory()->create(['employer_id' => $employer->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'status' => 'published',
            'deadline' => now()->addMonth()->toDateString(),
        ]);

        $invitation = InterviewInvitation::create([
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'employer_id' => $employer->id,
            'subject' => 'Interview',
            'interview_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        $this->actingAs($candidate)
            ->patch(route('job-seeker.interview-invitations.respond', $invitation), ['response' => 'accepted'])
            ->assertRedirect(route('job-seeker.interview-invitations.index'));

        $this->assertSame('accepted', $invitation->fresh()->status);
    }
}
