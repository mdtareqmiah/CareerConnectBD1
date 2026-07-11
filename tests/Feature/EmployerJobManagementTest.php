<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerJobManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $employer;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Employer', 'slug' => 'employer']);
        $this->employer = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_employer_can_view_their_own_jobs_index_with_filters_and_stats()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        Job::factory()->create(['company_id' => $company->id, 'title' => 'First Job', 'status' => 'draft']);
        Job::factory()->create(['company_id' => $company->id, 'title' => 'Second Job', 'status' => 'published', 'deadline' => now()->addDays(10)->toDateString()]);
        Job::factory()->create(['company_id' => $company->id, 'title' => 'Expired Job', 'status' => 'published', 'deadline' => now()->subDays(1)->toDateString()]);

        $this->actingAs($this->employer);

        $response = $this->get('/jobs?search=First&status=draft&sort=oldest');

        $response->assertStatus(200);
        $response->assertSee('First Job');
        $response->assertSee('Total Jobs');
        $response->assertSee('Published Jobs');
        $response->assertSee('Draft Jobs');
        $response->assertSee('Closed / Expired Jobs');
    }

    public function test_employer_can_duplicate_a_job_as_draft()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'published']);

        $this->actingAs($this->employer);

        $response = $this->post("/employer/jobs/{$job->id}/duplicate");

        $response->assertRedirect();
        $this->assertDatabaseHas('job_listings', ['title' => $job->title, 'status' => 'draft']);
    }

    public function test_employer_can_restore_soft_deleted_job()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $job->delete();

        $this->actingAs($this->employer);

        $response = $this->post("/employer/jobs/{$job->id}/restore");

        $response->assertRedirect('/employer/jobs/trash');
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'deleted_at' => null]);
    }

    public function test_employer_can_force_delete_soft_deleted_job()
    {
        $company = Company::factory()->create(['employer_id' => $this->employer->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $job->delete();

        $this->actingAs($this->employer);

        $response = $this->delete("/employer/jobs/{$job->id}/force-delete");

        $response->assertRedirect('/employer/jobs/trash');
        $this->assertDatabaseMissing('job_listings', ['id' => $job->id]);
    }

    public function test_employer_cannot_access_another_employers_job()
    {
        $other = User::factory()->create(['role_id' => Role::where('slug', 'employer')->first()->id]);
        $company = Company::factory()->create(['employer_id' => $other->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);

        $this->actingAs($this->employer);

        $response = $this->get("/jobs/{$job->id}");

        $response->assertStatus(403);
    }
}
