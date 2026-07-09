<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            [
                'name' => 'Job Seeker',
                'description' => 'Job Seeker',
                'is_active' => true,
            ]
        );

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->role_id);
        $this->assertSame(Role::where('slug', 'job-seeker')->value('id'), $user->role_id);
        $response->assertRedirect('/job-seeker/dashboard');
    }
}
