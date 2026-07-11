<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployerApplicationReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['slug' => 'employer'], ['name' => 'Employer', 'description' => 'Employer', 'is_active' => true]);
        $this->employer = User::factory()->create(['role_id' => $role->id]);
    }

    private function createJobSeekerWithProfile(): array
    {
        $role = Role::firstOrCreate(['slug' => 'job-seeker'], ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'Sadia',
            'last_name' => 'Ahmed',
            'phone' => '01711223344',
            'address' => 'Dhaka, Bangladesh',
            'gender' => 'Female',
            'date_of_birth' => '1995-05-12',
        ]);

        return [$user, $profile];
    }

    public function test_employer_sees_applicant_profile_sections(): void
    {
        [$user, $profile] = $this->createJobSeekerWithProfile();
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/applicant.pdf', 'file_name' => 'applicant.pdf', 'file_type' => 'pdf', 'file_size' => 2048]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'resume_id' => $resume->id, 'status' => 'reviewed', 'cover_letter' => 'I am highly interested.']);

        Education::create(['job_seeker_profile_id' => $profile->id, 'degree' => 'B.Sc.', 'field_of_study' => 'Computer Science', 'institution_name' => 'DU', 'board_or_university' => 'Dhaka University', 'result' => '3.75', 'passing_year' => 2017]);
        Experience::create(['job_seeker_profile_id' => $profile->id, 'company_name' => 'ABC Ltd', 'job_title' => 'Software Engineer', 'employment_type' => 'Full-time', 'start_date' => '2018-02-01', 'end_date' => '2021-04-30', 'job_description' => 'Built web applications.']);
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee('Applicant Review');
        $response->assertSee('Sadia Ahmed');
        $response->assertSee('Dhaka, Bangladesh');
        $response->assertSee('B.Sc.');
        $response->assertSee('Dhaka University');
        $response->assertSee('Software Engineer');
        $response->assertSee('Laravel');
        $response->assertSee('applicant.pdf');
        $response->assertSee('Preview Resume');
        $response->assertSee('Download Resume');
    }

    public function test_employer_can_preview_pdf_resume(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerWithProfile();
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/preview.pdf', 'file_name' => 'preview.pdf', 'file_type' => 'pdf', 'file_size' => 4096]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'resume_id' => $resume->id]);

        Storage::disk('public')->put('resumes/preview.pdf', '%PDF-1.4');

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.resume.preview', $application));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('%PDF-1.4', $response->streamedContent());
    }

    public function test_docx_resume_shows_download_only(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerWithProfile();
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/resume.docx', 'file_name' => 'resume.docx', 'file_type' => 'docx', 'file_size' => 5120]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'resume_id' => $resume->id]);

        Storage::disk('public')->put('resumes/resume.docx', 'DOCX');
        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.show', $application));

        $response->assertStatus(200);
        $response->assertDontSee('Preview Resume');
        $response->assertSee('Download Resume');
    }

    public function test_employer_cannot_access_another_employer_application(): void
    {
        [$user, $profile] = $this->createJobSeekerWithProfile();
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $otherRole = Role::firstOrCreate(['slug' => 'employer'], ['name' => 'Employer', 'description' => 'Employer', 'is_active' => true]);
        $otherEmployer = User::factory()->create(['role_id' => $otherRole->id]);
        $otherCompany = Company::factory()->create(['employer_id' => $otherEmployer->id]);
        $job = Job::factory()->create(['company_id' => $otherCompany->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/other.pdf', 'file_name' => 'other.pdf', 'file_type' => 'pdf', 'file_size' => 1024]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'resume_id' => $resume->id]);

        $this->actingAs($this->employer);

        $response = $this->get(route('employer.applications.show', $application));
        $response->assertStatus(403);

        $response = $this->get(route('employer.applications.resume.preview', $application));
        $response->assertStatus(403);

        $response = $this->get(route('employer.applications.resume.download', $application));
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_receives_403(): void
    {
        [$user, $profile] = $this->createJobSeekerWithProfile();
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/applicant.pdf', 'file_name' => 'applicant.pdf', 'file_type' => 'pdf', 'file_size' => 2048]);
        $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'resume_id' => $resume->id]);

        $response = $this->get(route('employer.applications.show', $application));
        $response->assertRedirect(route('login'));
    }
}
