<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_users_are_redirected_to_admin_after_login(): void
    {
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator',
            'is_active' => true,
        ]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }

    public function test_employer_users_are_redirected_to_employer_after_login(): void
    {
        $role = Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer',
            'is_active' => true,
        ]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/employer');
    }

    public function test_job_seeker_users_are_redirected_to_the_dashboard_after_login(): void
    {
        $role = Role::create([
            'name' => 'Job Seeker',
            'slug' => 'job-seeker',
            'description' => 'Job Seeker',
            'is_active' => true,
        ]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/job-seeker/dashboard');
    }

    public function test_users_without_role_are_redirected_to_dashboard_after_login(): void
    {
        $user = User::factory()->create(['role_id' => null]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
