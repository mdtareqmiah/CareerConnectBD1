<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $name, string $slug): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function createUserWithRole(string $slug, string $name): User
    {
        $role = $this->createRole($name, $slug);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createUserWithRole('admin', 'Admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Admin Dashboard');
    }

    public function test_employer_is_forbidden_from_admin_dashboard(): void
    {
        $employer = $this->createUserWithRole('employer', 'Employer');

        $response = $this->actingAs($employer)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_job_seeker_is_forbidden_from_admin_dashboard(): void
    {
        $jobSeeker = $this->createUserWithRole('job-seeker', 'Job Seeker');

        $response = $this->actingAs($jobSeeker)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_statistics_are_visible(): void
    {
        $admin = $this->createUserWithRole('admin', 'Admin');

        $employerRole = $this->createRole('Employer', 'employer');
        $jobSeekerRole = $this->createRole('Job Seeker', 'job-seeker');

        $employer = User::factory()->create([
            'role_id' => $employerRole->id,
            'created_at' => now(),
        ]);

        $jobSeeker = User::factory()->create([
            'role_id' => $jobSeekerRole->id,
            'created_at' => now(),
        ]);

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'created_at' => now(),
        ]);

        $publishedJob = Job::factory()->create([
            'company_id' => $company->id,
            'status' => 'published',
            'created_at' => now(),
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'status' => 'draft',
            'created_at' => now(),
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'status' => 'archived',
            'created_at' => now(),
        ]);

        $profile = JobSeekerProfile::factory()->create([
            'user_id' => $jobSeeker->id,
        ]);

        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
        ]);

        JobApplication::factory()->create([
            'job_id' => $publishedJob->id,
            'user_id' => $jobSeeker->id,
            'resume_id' => $resume->id,
            'created_at' => now(),
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Total Users');
        $response->assertSee('Total Employers');
        $response->assertSee('Total Job Seekers');
        $response->assertSee('Total Companies');
        $response->assertSee('Total Jobs');
        $response->assertSee('Published Jobs');
        $response->assertSee('Draft Jobs');
        $response->assertSee('Closed Jobs');
        $response->assertSee('Applications');
        $response->assertSeeText("Today's Registrations");
        $response->assertSeeText("Today's Jobs");
        $response->assertSeeText("Today's Applications");
    }

    public function test_default_seeded_admin_can_login_and_access_all_admin_routes(): void
    {
        $this->seed(AdminSeeder::class);

        $loginResponse = $this->post('/login', [
            'email' => 'admin@careerconnectbd.com',
            'password' => 'Admin12345',
        ]);

        $loginResponse->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();

        $adminRoutes = [
            '/admin',
            '/admin/dashboard',
            '/admin/users',
            '/admin/employers',
            '/admin/companies',
            '/admin/jobs',
            '/admin/applications',
            '/admin/reports',
            '/admin/cms',
            '/admin/settings',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            if ($route === '/admin') {
                $response->assertRedirect('/admin/dashboard');
                continue;
            }

            $response->assertOk();
        }
    }
}
