<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminCompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug, string $name): Role
    {
        return Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => $name, 'is_active' => true]
        );
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role_id' => $this->createRole('admin', 'Admin')->id,
            'is_active' => true,
        ]);
    }

    private function createEmployer(string $email = 'employer@example.com'): User
    {
        return User::factory()->create([
            'role_id' => $this->createRole('employer', 'Employer')->id,
            'email' => $email,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_list_employers(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        Company::factory()->create([
            'employer_id' => $employer->id,
            'verification_status' => 'pending',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.employers.index'));

        $response->assertOk();
        $response->assertSee('Employer Management');
        $response->assertSee($employer->email);
    }

    public function test_admin_can_list_companies(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'company_name' => 'Skyline Tech Ltd',
            'verification_status' => 'pending',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.companies.index'));

        $response->assertOk();
        $response->assertSee('Company Management');
        $response->assertSee($company->company_name);
    }

    public function test_admin_can_search_companies_and_employers(): void
    {
        $admin = $this->createAdmin();

        $targetEmployer = $this->createEmployer('target-employer@example.com');
        Company::factory()->create([
            'employer_id' => $targetEmployer->id,
            'company_name' => 'Target Dynamics',
            'industry' => 'Aerospace',
            'verification_status' => 'pending',
            'is_active' => true,
        ]);

        $otherEmployer = $this->createEmployer('other-employer@example.com');
        Company::factory()->create([
            'employer_id' => $otherEmployer->id,
            'company_name' => 'Other Dynamics',
            'industry' => 'Retail',
            'verification_status' => 'pending',
            'is_active' => true,
        ]);

        $companyResponse = $this->actingAs($admin)->get(route('admin.companies.index', ['search' => 'Target Dynamics']));
        $companyResponse->assertOk();
        $companyResponse->assertSee('Target Dynamics');
        $companyResponse->assertDontSee('Other Dynamics');

        $employerResponse = $this->actingAs($admin)->get(route('admin.employers.index', ['search' => 'target-employer@example.com']));
        $employerResponse->assertOk();
        $employerResponse->assertSee('target-employer@example.com');
        $employerResponse->assertDontSee('other-employer@example.com');
    }

    public function test_admin_can_filter_company_list(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        Company::factory()->create([
            'employer_id' => $employer->id,
            'company_name' => 'Verified Active Co',
            'verification_status' => 'verified',
            'is_active' => true,
        ]);

        Company::factory()->create([
            'employer_id' => $employer->id,
            'company_name' => 'Pending Inactive Co',
            'verification_status' => 'pending',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.companies.index', [
            'verification' => 'verified',
            'status' => 'active',
        ]));

        $response->assertOk();
        $response->assertSee('Verified Active Co');
        $response->assertDontSee('Pending Inactive Co');
    }

    public function test_admin_can_approve_company(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'verification_status' => 'pending',
            'verified_at' => null,
            'verified_by' => null,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.companies.approve', $company));

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'verification_status' => 'verified',
            'verified_by' => $admin->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_reject_company(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'verification_status' => 'pending',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.companies.reject', $company));

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'verification_status' => 'rejected',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_suspend_company(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployer();

        $company = Company::factory()->create([
            'employer_id' => $employer->id,
            'verification_status' => 'verified',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.companies.suspend', $company));

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'is_active' => false,
        ]);
    }

    public function test_employer_is_forbidden_job_seeker_forbidden_guest_redirected(): void
    {
        $this->createRole('job-seeker', 'Job Seeker');

        $employer = $this->createEmployer('forbidden-employer@example.com');
        $jobSeeker = User::factory()->create([
            'role_id' => Role::where('slug', 'job-seeker')->value('id'),
        ]);

        $this->actingAs($employer)->get(route('admin.companies.index'))->assertForbidden();
        $this->actingAs($jobSeeker)->get(route('admin.companies.index'))->assertForbidden();

        Auth::guard('web')->logout();
        $this->get(route('admin.companies.index'))->assertRedirect(route('login'));
    }
}
