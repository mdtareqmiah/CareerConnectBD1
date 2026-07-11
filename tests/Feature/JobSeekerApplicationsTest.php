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
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobSeekerApplicationsTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => ucfirst(str_replace('-', ' ', $slug)),
                'description' => ucfirst(str_replace('-', ' ', $slug)),
                'is_active' => true,
            ]
        );
    }

    private function createJobSeeker(): User
    {
        $role = $this->createRole('job-seeker');

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('job-seeker.applications.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_employer_is_forbidden(): void
    {
        $role = $this->createRole('employer');
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index'));

        $response->assertStatus(403);
    }

    public function test_admin_is_forbidden(): void
    {
        $role = $this->createRole('admin');
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index'));

        $response->assertStatus(403);
    }

    public function test_job_seeker_sees_own_applications(): void
    {
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Test Role']);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf']);
        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
            'cover_letter' => 'I am interested in this role.',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Role');
        $response->assertSee($company->company_name);
        $response->assertSee('Pending');
        $response->assertSee('I am interested in this role.');
    }

    public function test_job_seeker_cannot_see_another_users_applications(): void
    {
        $user = $this->createJobSeeker();
        $otherUser = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $otherUser->id]);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Other Role']);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf']);
        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $otherUser->id,
            'resume_id' => $resume->id,
            'status' => 'reviewed',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.show', $application));

        $response->assertStatus(403);
    }

    public function test_search_by_job_title_and_company_name_works(): void
    {
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create(['company_name' => 'Acme Corp']);
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Laravel Developer']);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf']);
        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index', ['search' => 'Acme']));
        $response->assertStatus(200);
        $response->assertSee('Laravel Developer');

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index', ['search' => 'Laravel']));
        $response->assertStatus(200);
        $response->assertSee('Acme Corp');
    }

    public function test_status_filter_works(): void
    {
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Backend Engineer']);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf']);
        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index', ['status' => 'shortlisted']));

        $response->assertStatus(200);
        $response->assertSee('Shortlisted');
    }

    public function test_pagination_works(): void
    {
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create();
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf']);

        Job::factory()->count(15)->create(['company_id' => $company->id])->each(function ($job) use ($user, $resume) {
            JobApplication::factory()->create([
                'job_id' => $job->id,
                'user_id' => $user->id,
                'resume_id' => $resume->id,
                'status' => 'pending',
            ]);
        });

        $response = $this->actingAs($user)->get(route('job-seeker.applications.index'));

        $response->assertStatus(200);
        $response->assertSee('Showing');
        $response->assertSee('results');
    }

    public function test_detail_page_works(): void
    {
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Frontend Engineer']);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/test.pdf', 'file_name' => 'test.pdf']);
        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'reviewed',
            'cover_letter' => 'Looking forward to contributing.',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee('Frontend Engineer');
        $response->assertSee('Company Details');
        $response->assertSee('Review the application');
        $response->assertSee('Download Resume');
    }

    public function test_resume_download_works(): void
    {
        Storage::fake('public');
        $user = $this->createJobSeeker();
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id]);
        $resume = Resume::factory()->create(['job_seeker_profile_id' => $profile->id, 'file_path' => 'resumes/download-test.pdf', 'file_name' => 'download-test.pdf']);
        Storage::disk('public')->put('resumes/download-test.pdf', 'resume-content');
        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('job-seeker.resumes.download', $resume));

        $response->assertOk();
        $response->assertDownload('download-test.pdf');
    }
}
