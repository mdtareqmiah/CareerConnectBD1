<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $slug): User
    {
        $role = Role::create([
            'name' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'description' => ucfirst(str_replace('-', ' ', $slug)),
            'is_active' => true,
        ]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_employer_cannot_access_job_seeker_dashboard(): void
    {
        $user = $this->createUser('employer');

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_cannot_access_job_seeker_dashboard(): void
    {
        $user = $this->createUser('admin');

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertForbidden();
    }
}
