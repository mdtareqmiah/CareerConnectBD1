<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JobApplicationFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_applications_table_migration_creates_expected_columns(): void
    {
        Artisan::call('migrate');

        $this->assertTrue(Schema::hasTable('job_applications'));
        $this->assertTrue(Schema::hasColumns('job_applications', [
            'id',
            'job_id',
            'user_id',
            'resume_id',
            'cover_letter',
            'status',
            'applied_at',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_job_application_relationships_are_defined(): void
    {
        $resume = Resume::factory()->create();
        $jobApplication = JobApplication::factory()->create(['resume_id' => $resume->id]);

        $this->assertInstanceOf(Job::class, $jobApplication->job);
        $this->assertInstanceOf(User::class, $jobApplication->user);
        $this->assertInstanceOf(Resume::class, $jobApplication->resume);

        $this->assertTrue($jobApplication->job->applications->contains($jobApplication));
        $this->assertTrue($jobApplication->user->jobApplications->contains($jobApplication));
        $this->assertTrue($resume->jobApplications->contains($jobApplication));
    }

    public function test_job_application_unique_constraint_prevents_duplicate_applications(): void
    {
        $job = Job::factory()->create();
        $user = User::factory()->create();

        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_access_job_application_routes(): void
    {
        $jobApplication = JobApplication::factory()->create();
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->get(route('job-applications.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('job-applications.create', ['job_id' => $job->id]));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('job-applications.store'), []);
        $response->assertRedirect(route('login'));

        $response = $this->get(route('job-applications.show', $jobApplication));
        $response->assertRedirect(route('login'));
    }

    public function test_employer_cannot_access_job_application_routes(): void
    {
        $role = Role::create(['name' => 'Employer', 'slug' => 'employer', 'description' => 'Employer', 'is_active' => true]);
        $employer = User::factory()->create(['role_id' => $role->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->actingAs($employer)->get(route('job-applications.index'));
        $response->assertForbidden();

        $response = $this->actingAs($employer)->get(route('job-applications.create', ['job_id' => $job->id]));
        $response->assertForbidden();
    }

    public function test_job_seeker_can_access_job_application_routes(): void
    {
        $role = Role::create(['name' => 'Job Seeker', 'slug' => 'job-seeker', 'description' => 'Job Seeker', 'is_active' => true]);
        $jobSeeker = User::factory()->create(['role_id' => $role->id]);
        $job = Job::factory()->create(['status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);

        $response = $this->actingAs($jobSeeker)->get(route('job-applications.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($jobSeeker)->get(route('job-applications.create', ['job_id' => $job->id]));
        $response->assertStatus(200);
    }
}
