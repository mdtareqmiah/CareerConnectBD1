<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployerApplicationManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Employer', 'slug' => 'employer']);
        $this->employer = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_employer_can_view_their_applications_list(): void
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'status' => 'pending']);

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.index'));

        $response->assertStatus(200);
        $response->assertSee($application->user->name);
        $response->assertSee($application->job->title);
        $response->assertSee('Pending');
    }

    public function test_employer_can_view_application_details(): void
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'status' => 'reviewed']);

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee($application->user->name);
        $response->assertSee($application->job->title);
        $response->assertSee('Reviewed');
    }

    public function test_employer_can_update_application_status(): void
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'status' => 'pending']);

        $this->actingAs($this->employer);

        $response = $this->patch(route('employer.applications.update_status', $application), [
            'status' => 'shortlisted',
        ]);

        $response->assertRedirect(route('employer.applications.show', $application));
        $response->assertSessionHas('success', 'Application status updated successfully.');
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => 'shortlisted',
        ]);
    }

    public function test_employer_can_download_applicant_resume(): void
    {
        Storage::fake('public');

        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $resume = Resume::factory()->create(['file_path' => 'resumes/test-resume.pdf', 'file_name' => 'test-resume.pdf']);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'resume_id' => $resume->id]);

        Storage::disk('public')->put('resumes/test-resume.pdf', 'resume-content');

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.resume.download', $application));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
        $this->assertStringContainsString('test-resume.pdf', $response->headers->get('content-disposition'));
    }

    public function test_employer_cannot_view_other_employer_applications(): void
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $otherEmployer = User::factory()->create(['role_id' => Role::where('slug', 'employer')->first()->id]);
        $otherCompany = Company::factory()->create(['employer_id' => $otherEmployer->id]);
        $job = Job::factory()->create(['company_id' => $otherCompany->id, 'status' => 'published', 'deadline' => now()->addDays(5)->toDateString()]);
        $application = JobApplication::factory()->create(['job_id' => $job->id]);

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.show', $application));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_employer_application_routes(): void
    {
        $application = JobApplication::factory()->create();

        $response = $this->get(route('employer.applications.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('employer.applications.show', $application));
        $response->assertRedirect(route('login'));
    }
}
