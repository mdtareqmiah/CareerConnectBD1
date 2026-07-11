<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApplicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug): Role
    {
        return Role::create([
            'name' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'description' => ucfirst(str_replace('-', ' ', $slug)),
            'is_active' => true,
        ]);
    }

    public function test_guest_redirected_to_login_when_clicking_apply(): void
    {
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->get(route('jobs.apply', $job));

        $response->assertRedirect(route('login'));
        $this->assertEquals(route('jobs.show', $job), session('url.intended'));
        $this->assertEquals('Please complete your application.', session('status'));
    }

    public function test_job_seeker_can_apply_for_job(): void
    {
        $role = $this->createRole('job-seeker');
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id]);

        $response = $this->actingAs($user)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('Valid cover letter ', 4),
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('status', 'Application submitted successfully.');
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
        ]);
    }

    public function test_duplicate_prevented(): void
    {
        $role = $this->createRole('job-seeker');
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id]);

        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->actingAs($user)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('Valid cover letter ', 4),
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('error', 'You have already applied for this job.');
    }

    public function test_resume_ownership_validated(): void
    {
        $role = $this->createRole('job-seeker');
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $otherUser = User::factory()->create(['role_id' => $role->id]);
        $otherProfile = JobSeekerProfile::factory()->create(['user_id' => $otherUser->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $otherProfile->id]);

        $response = $this->actingAs($user)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('Valid cover letter ', 4),
        ]);

        $response->assertSessionHasErrors('resume_id');
    }

    public function test_expired_job_blocked(): void
    {
        $role = $this->createRole('job-seeker');
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->subDay()->toDateString()]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id]);

        $response = $this->actingAs($user)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('Valid cover letter ', 4),
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('error', 'This job is no longer accepting applications.');
    }

    public function test_draft_job_blocked(): void
    {
        $role = $this->createRole('job-seeker');
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $job = Job::factory()->create(['status' => 'draft', 'deadline' => now()->addDays(5)->toDateString()]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id]);

        $response = $this->actingAs($user)->post(route('job-applications.store'), [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('Valid cover letter ', 4),
        ]);

        $response->assertSessionHasErrors('job_id');
    }

    public function test_employer_forbidden_from_applying(): void
    {
        $role = $this->createRole('employer');
        $user = User::factory()->create(['role_id' => $role->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->actingAs($user)->get(route('jobs.apply', $job));

        $response->assertStatus(403);
    }

    public function test_admin_forbidden_from_applying(): void
    {
        $role = $this->createRole('admin');
        $user = User::factory()->create(['role_id' => $role->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->actingAs($user)->get(route('jobs.apply', $job));

        $response->assertStatus(403);
    }
}
