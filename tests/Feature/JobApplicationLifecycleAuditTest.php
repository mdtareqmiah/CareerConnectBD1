<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class JobApplicationLifecycleAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_job_application_lifecycle_through_real_routes(): void
    {
        $employerRole = Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer',
            'is_active' => true,
        ]);

        $jobSeekerRole = Role::create([
            'name' => 'Job Seeker',
            'slug' => 'job-seeker',
            'description' => 'Job Seeker',
            'is_active' => true,
        ]);

        $employer = User::factory()->create([
            'role_id' => $employerRole->id,
            'name' => 'Audit Employer',
            'email' => 'employer.audit@example.com',
        ]);

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'company_name' => 'Audit Employer Ltd',
        ]);

        $jobPayload = [
            'title' => 'Lifecycle Test Engineer',
            'vacancy' => 2,
            'job_type' => 'Full Time',
            'workplace' => 'Remote',
            'employment_status' => 'Full Time',
            'experience_level' => 'Mid Level',
            'education_level' => 'Bachelor',
            'salary_type' => 'Monthly',
            'salary_min' => 50000,
            'salary_max' => 80000,
            'location' => 'Dhaka',
            'deadline' => now()->addDays(14)->toDateString(),
            'description' => 'This is a lifecycle test job for validating the application workflow end to end.',
            'responsibilities' => "Validate lifecycle flow\nReview application data\nVerify notifications",
            'requirements' => "Laravel\nPHP\nMySQL",
            'benefits' => 'Remote first team and flexible hours.',
            'status' => 'published',
            'published_at' => now(),
        ];

        $this->actingAs($employer)
            ->post(route('jobs.store'), $jobPayload)
            ->assertRedirect();

        $job = Job::latest('id')->with('company')->first();

        $this->assertNotNull($job);
        $this->assertSame($company->id, $job->company_id);
        $this->assertSame($employer->id, $job->company->employer_id);
        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'company_id' => $company->id,
            'title' => 'Lifecycle Test Engineer',
        ]);

        $jobSeeker = User::factory()->create([
            'role_id' => $jobSeekerRole->id,
            'name' => 'Audit Job Seeker',
            'email' => 'seeker.audit@example.com',
        ]);

        $profile = JobSeekerProfile::factory()->create([
            'user_id' => $jobSeeker->id,
        ]);

        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Lifecycle Resume',
            'file_name' => 'lifecycle-resume.pdf',
            'file_path' => 'resumes/lifecycle-resume.pdf',
            'file_type' => 'pdf',
            'is_default' => true,
            'is_active' => true,
        ]);

        $this->actingAs($jobSeeker)
            ->get(route('jobs.show', $job))
            ->assertStatus(200);

        $this->actingAs($jobSeeker)
            ->get(route('jobs.apply', $job))
            ->assertRedirect(route('job-applications.create', ['job_id' => $job->id]));

        $this->actingAs($jobSeeker)
            ->get(route('job-applications.create', ['job_id' => $job->id]))
            ->assertStatus(200);

        $coverLetter = trim(str_repeat('I am a strong fit for this role. ', 3));

        $this->actingAs($jobSeeker)
            ->post(route('job-applications.store'), [
                'job_id' => $job->id,
                'resume_id' => $resume->id,
                'cover_letter' => $coverLetter,
            ])
            ->assertRedirect(route('jobs.show', $job))
            ->assertSessionHas('success', 'Application submitted successfully.');

        $application = JobApplication::latest()->first();

        $this->assertNotNull($application);
        $this->assertSame($job->id, $application->job_id);
        $this->assertSame($jobSeeker->id, $application->user_id);
        $this->assertSame('pending', $application->status);
        $this->assertSame($resume->id, $application->resume_id);
        $this->assertSame($coverLetter, $application->cover_letter);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $employer->id,
        ]);

        $employerNotification = $employer->notifications()->latest()->first();
        $this->assertNotNull($employerNotification);
        $this->assertSame('job_applied', $employerNotification->data['type'] ?? null);
        $this->assertStringContainsString('New application for Lifecycle Test Engineer', $employerNotification->data['title'] ?? '');

        $this->actingAs($employer)
            ->get(route('employer.applications.index'))
            ->assertStatus(200)
            ->assertSee('Lifecycle Test Engineer')
            ->assertSee('Audit Job Seeker');

        $this->actingAs($employer)
            ->get(route('employer.applications.show', $application))
            ->assertStatus(200)
            ->assertSee('lifecycle-resume.pdf')
            ->assertSee($coverLetter);

        $this->actingAs($employer)
            ->patch(route('employer.applications.update_status', $application), [
                'status' => 'rejected',
            ])
            ->assertRedirect(route('employer.applications.show', $application))
            ->assertSessionHas('success', 'Application status updated successfully.');

        $application->refresh();
        $this->assertSame('rejected', $application->status);

        $jobSeekerNotification = $jobSeeker->notifications()->latest()->first();
        $this->assertNotNull($jobSeekerNotification);
        $this->assertSame('application_status_changed', $jobSeekerNotification->data['type'] ?? null);
        $this->assertStringContainsString('Rejected', $jobSeekerNotification->data['message'] ?? '');

        $this->actingAs($jobSeeker)
            ->get(route('job-seeker.applications.index'))
            ->assertStatus(200)
            ->assertSee('Lifecycle Test Engineer')
            ->assertSee('Rejected');

        $this->assertSame($application->id, JobApplication::latest()->first()->id);
        $this->assertGreaterThanOrEqual(2, DatabaseNotification::query()->count());
    }
}
