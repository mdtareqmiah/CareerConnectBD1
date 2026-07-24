<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Role;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplyJobFeatureTest extends TestCase
{
    public function test_job_seeker_can_apply_with_saved_resume(): void
    {
        $employer = User::factory()->create();
        $company = Company::factory()->create(['employer_id' => $employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $seeker = $this->createJobSeeker();
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $seeker->jobSeekerProfile->id]);

        $response = $this->actingAs($seeker)->post('/job-applications', [
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => str_repeat('This is a cover letter for the job application. ', 5),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'user_id' => $seeker->id,
            'resume_id' => $resume->id,
        ]);
    }

    public function test_job_seeker_can_apply_with_file_upload(): void
    {
        Storage::fake('public');

        $employer = User::factory()->create();
        $company = Company::factory()->create(['employer_id' => $employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $seeker = $this->createJobSeeker();

        $response = $this->actingAs($seeker)->post('/job-applications', [
            'job_id' => $job->id,
            'resume_file' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
            'cover_letter' => str_repeat('This is a cover letter for the job application. ', 5),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'user_id' => $seeker->id,
        ]);
    }

    public function test_employer_can_view_applications(): void
    {
        $employer = User::factory()->create();
        $company = Company::factory()->create(['employer_id' => $employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $seeker = $this->createJobSeeker();

        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $seeker->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employer)->get('/employer/applications');
        $response->assertSuccessful();
        $response->assertSee('Applied');
    }

    public function test_job_seeker_can_view_applications(): void
    {
        $seeker = $this->createJobSeeker();

        JobApplication::factory()->create([
            'user_id' => $seeker->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($seeker)->get('/job-seeker/applications');
        $response->assertSuccessful();
        $response->assertSee('My Applications');
    }

    private function createJobSeeker(): User
    {
        $role = Role::where('slug', 'job-seeker')->firstOrFail();
        $user = User::factory()->create(['role_id' => $role->id]);

        $user->jobSeekerProfile()->create([
            'first_name' => 'Test',
            'last_name' => 'Seeker',
        ]);

        return $user;
    }
}
