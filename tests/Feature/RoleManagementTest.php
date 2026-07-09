<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdminUser(): User
    {
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator',
            'is_active' => true,
        ]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_admin_can_list_roles(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/roles');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_admin_can_create_a_role_with_valid_data(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post('/roles', [
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer role',
            'is_active' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['slug' => 'employer']);
    }

    public function test_role_creation_requires_valid_data(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->from('/roles')->post('/roles', [
            'name' => '',
            'slug' => '',
            'description' => null,
            'is_active' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['name', 'slug', 'is_active']);
    }

    public function test_non_admin_users_cannot_access_role_routes(): void
    {
        $role = Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer role',
            'is_active' => true,
        ]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/roles');

        $response->assertForbidden();
    }
}
