<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeekerUser(): User
    {
        $role = Role::create([
            'name' => 'Job Seeker',
            'slug' => 'job-seeker',
            'description' => 'Job Seeker',
            'is_active' => true,
        ]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_guest_users_are_redirected_to_login_for_job_seeker_dashboard(): void
    {
        $response = $this->get('/job-seeker/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_job_seeker_can_access_the_dashboard_and_view_content(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Job Seeker Dashboard');
        $response->assertSee('Welcome');
    }

    public function test_dashboard_route_redirects_job_seekers_to_the_job_seeker_dashboard(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/job-seeker/dashboard');
    }

    public function test_authenticated_users_can_open_the_profile_page(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }
}
