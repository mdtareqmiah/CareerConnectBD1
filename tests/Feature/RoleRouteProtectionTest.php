<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function createRole(string $name, string $slug): Role
    {
        return Role::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $name,
            'is_active' => true,
        ]);
    }

    public function test_guests_are_redirected_to_login_for_admin_routes(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_users_with_the_correct_role_can_access_their_route(): void
    {
        $role = $this->createRole('Admin', 'admin');
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Admin access granted');
    }

    public function test_users_with_the_wrong_role_receive_forbidden_for_admin_routes(): void
    {
        $role = $this->createRole('Employer', 'employer');
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }
}
