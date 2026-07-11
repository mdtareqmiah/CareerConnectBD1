<?php

namespace Tests\Feature\Auth;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/employer/register');

        $response->assertStatus(200);
        $response->assertSee('Register as Employer');
        $response->assertSee('Confirm Password');
    }

    public function test_employer_users_can_register(): void
    {
        $role = Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer',
            'is_active' => true,
        ]);

        $response = $this->post('/employer/register', [
            'name' => 'Employer User',
            'email' => 'employer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', 'employer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame($role->id, $user->role_id);
        $response->assertRedirect('/employer');
    }

    public function test_employer_login_redirects_to_company_create_when_no_company_exists(): void
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

        $this->actingAs($user);
        $response = $this->get('/employer');

        $response->assertRedirect(route('company.create', absolute: false));
    }

    public function test_employer_login_redirects_to_dashboard_when_company_exists(): void
    {
        $role = Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'description' => 'Employer',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role_id' => $role->id]);
        Company::factory()->create(['employer_id' => $user->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/employer');

        $this->actingAs($user);
        $response = $this->get('/employer');

        $response->assertRedirect(route('employer.dashboard', absolute: false));
    }
}
