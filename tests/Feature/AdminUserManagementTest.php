<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
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
        $adminRole = $this->createRole('admin', 'Admin');

        return User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_list_users(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('User Management');
    }

    public function test_admin_can_create_admin_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('admin', 'Admin');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'role_id' => $role->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'new-admin@example.com',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_employer_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('employer', 'Employer');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Employer',
            'email' => 'new-employer@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'role_id' => $role->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'new-employer@example.com',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_create_job_seeker_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('job-seeker', 'Job Seeker');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Job Seeker',
            'email' => 'new-seeker@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'role_id' => $role->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'new-seeker@example.com',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_edit_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('job-seeker', 'Job Seeker');
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated-user@example.com',
            'password' => '',
            'password_confirmation' => '',
            'role_id' => $role->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated-user@example.com',
        ]);
    }

    public function test_admin_can_deactivate_and_activate_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('job-seeker', 'Job Seeker');
        $user = User::factory()->create(['role_id' => $role->id, 'is_active' => true]);

        $deactivateResponse = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user));
        $deactivateResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);

        $activateResponse = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user));
        $activateResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => true]);
    }

    public function test_admin_can_soft_delete_and_restore_user(): void
    {
        $admin = $this->createAdmin();
        $role = $this->createRole('job-seeker', 'Job Seeker');
        $user = User::factory()->create(['role_id' => $role->id]);

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));
        $deleteResponse->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $restoreResponse = $this->actingAs($admin)->patch(route('admin.users.restore', $user->id));
        $restoreResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    public function test_admin_cannot_delete_or_deactivate_or_downgrade_self(): void
    {
        $admin = $this->createAdmin();
        $employerRole = $this->createRole('employer', 'Employer');

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
        $deleteResponse->assertSessionHasErrors('delete');

        $deactivateResponse = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin));
        $deactivateResponse->assertSessionHasErrors('status');

        $downgradeResponse = $this->actingAs($admin)->patch(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => '',
            'password_confirmation' => '',
            'role_id' => $employerRole->id,
            'is_active' => 1,
        ]);
        $downgradeResponse->assertSessionHasErrors('role_id');
    }

    public function test_employer_and_job_seeker_are_forbidden_and_guest_redirected(): void
    {
        $admin = $this->createAdmin();
        $employerRole = $this->createRole('employer', 'Employer');
        $jobSeekerRole = $this->createRole('job-seeker', 'Job Seeker');

        $employer = User::factory()->create(['role_id' => $employerRole->id]);
        $jobSeeker = User::factory()->create(['role_id' => $jobSeekerRole->id]);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($employer)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($jobSeeker)->get(route('admin.users.index'))->assertForbidden();

        Auth::guard('web')->logout();
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }
}
