<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOperationsMilestone84Test extends TestCase
{
    use RefreshDatabase;

    private function role(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role_id' => $this->role('admin', 'Admin')->id,
            'is_active' => true,
        ]);
    }

    private function employer(string $email = 'employer84@example.com'): User
    {
        return User::factory()->create([
            'role_id' => $this->role('employer', 'Employer')->id,
            'email' => $email,
            'is_active' => true,
        ]);
    }

    private function jobSeeker(string $email = 'seeker84@example.com'): User
    {
        return User::factory()->create([
            'role_id' => $this->role('job-seeker', 'Job Seeker')->id,
            'email' => $email,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_manage_jobs(): void
    {
        $admin = $this->admin();
        $employer = $this->employer();

        $company = Company::factory()->create(['employer_id' => $employer->id]);

        $job = Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Senior Laravel Developer',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.jobs.index', ['search' => 'Laravel', 'status' => 'draft']))
            ->assertOk()
            ->assertSee('Job Management')
            ->assertSee('Senior Laravel Developer');

        $this->actingAs($admin)->patch(route('admin.jobs.publish', $job->id))->assertRedirect();
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'status' => 'published']);

        $this->actingAs($admin)->patch(route('admin.jobs.close', $job->id))->assertRedirect();
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'status' => 'archived']);

        $this->actingAs($admin)->patch(route('admin.jobs.reopen', $job->id))->assertRedirect();
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'status' => 'published']);

        $this->actingAs($admin)->delete(route('admin.jobs.destroy', $job->id))->assertRedirect();
        $this->assertSoftDeleted('job_listings', ['id' => $job->id]);

        $this->actingAs($admin)->patch(route('admin.jobs.restore', $job->id))->assertRedirect();
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'deleted_at' => null]);
    }

    public function test_admin_can_manage_applications(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $employer = $this->employer('employer-app@example.com');
        $company = Company::factory()->create(['employer_id' => $employer->id, 'company_name' => 'Acme Corp']);
        $job = Job::factory()->create(['company_id' => $company->id, 'title' => 'Platform Engineer']);

        $candidate = $this->jobSeeker('candidate@app.test');
        $profile = JobSeekerProfile::factory()->create(['user_id' => $candidate->id]);

        $file = UploadedFile::fake()->create('candidate-resume.pdf', 200, 'application/pdf');
        $path = $file->store('resumes', 'public');

        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'file_name' => 'candidate-resume.pdf',
            'file_path' => $path,
            'file_type' => 'pdf',
        ]);

        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $candidate->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.applications.index', ['search' => 'Acme', 'status' => 'pending']))
            ->assertOk()
            ->assertSee('Application Management')
            ->assertSee('Acme Corp');

        $this->actingAs($admin)
            ->get(route('admin.applications.show', $application))
            ->assertOk()
            ->assertSee('Application Details');

        $this->actingAs($admin)
            ->get(route('admin.applications.resume.preview', $application))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.applications.resume.download', $application))
            ->assertOk();
    }

    public function test_admin_can_view_reports_and_update_settings(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Reports Dashboard');

        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), [
                'site_name' => 'CareerConnectBD Pro',
                'site_email' => 'site@example.com',
                'contact_email' => 'contact@example.com',
                'support_email' => 'support@example.com',
                'default_timezone' => 'UTC',
                'pagination_size' => 20,
                'maintenance_mode' => true,
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('system_settings', ['key' => 'site_name', 'value' => 'CareerConnectBD Pro']);
        $this->assertDatabaseHas('system_settings', ['key' => 'pagination_size', 'value' => '20']);
        $this->assertDatabaseHas('system_settings', ['key' => 'maintenance_mode', 'value' => '1']);

        $this->assertTrue(SystemSetting::where('key', 'site_name')->exists());
    }

    public function test_employer_forbidden_job_seeker_forbidden_guest_redirected(): void
    {
        $employer = $this->employer('forbidden84-employer@example.com');
        $jobSeeker = $this->jobSeeker('forbidden84-seeker@example.com');

        $this->actingAs($employer)->get(route('admin.jobs.index'))->assertForbidden();
        $this->actingAs($jobSeeker)->get(route('admin.jobs.index'))->assertForbidden();

        Auth::guard('web')->logout();
        $this->get(route('admin.jobs.index'))->assertRedirect(route('login'));
    }
}
