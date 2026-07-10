<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create employer role
        $role = Role::create(['name' => 'Employer', 'slug' => 'employer']);

        // Create employer user
        $this->employer = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_employer_can_access_dashboard()
    {
        $this->actingAs($this->employer);

        $response = $this->get('/employer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/employer/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_job_seeker_cannot_access_employer_dashboard()
    {
        $jobSeekerRole = Role::create(['name' => 'Job Seeker', 'slug' => 'job-seeker']);
        $jobSeeker = User::factory()->create(['role_id' => $jobSeekerRole->id]);

        $this->actingAs($jobSeeker);

        $response = $this->get('/employer/dashboard');

        $response->assertStatus(403);
    }

    public function test_dashboard_shows_company_creation_alert_if_no_company()
    {
        $this->actingAs($this->employer);

        $response = $this->get('/employer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Company Profile Not Set Up');
        $response->assertSee('Create Company Profile');
    }

    public function test_dashboard_shows_company_overview_if_company_exists()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);

        $this->actingAs($this->employer);

        $response = $this->get('/employer/dashboard');

        $response->assertStatus(200);
        $response->assertSee($company->company_name);
        $response->assertSee($company->industry);
    }

    public function test_dashboard_displays_statistics_cards()
    {
        $this->actingAs($this->employer);

        $response = $this->get('/employer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Job Postings');
        $response->assertSee('Applications Received');
        $response->assertSee('Profile Completion');
    }

    public function test_dashboard_redirects_to_login_if_not_authenticated()
    {
        $response = $this->get('/employer/dashboard');

        $response->assertRedirect('/login');
    }
}
