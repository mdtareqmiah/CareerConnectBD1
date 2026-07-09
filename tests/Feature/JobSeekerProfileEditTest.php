<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeekerUser(): User
    {
        $role = Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            [
                'name' => 'Job Seeker',
                'description' => 'Job Seeker',
                'is_active' => true,
            ]
        );

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_job_seeker_can_open_the_profile_edit_form(): void
    {
        $user = $this->createJobSeekerUser();
        JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'professional_title' => 'Backend Developer',
            'is_available_for_work' => true,
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/profile/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Your Profile');
        $response->assertSee('Ayesha');
    }

    public function test_job_seeker_without_profile_is_redirected_to_create_page(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->get('/job-seeker/profile/edit');

        $response->assertRedirect('/job-seeker/profile/create');
        $response->assertSessionHas('info');
    }

    public function test_job_seeker_cannot_edit_another_users_profile(): void
    {
        $owner = $this->createJobSeekerUser();
        $other = $this->createJobSeekerUser();
        $profile = JobSeekerProfile::create([
            'user_id' => $owner->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
        ]);

        $response = $this->actingAs($other)->get('/job-seeker/profile/edit/'.$profile->id);

        $response->assertForbidden();
    }

    public function test_job_seeker_profile_can_be_updated_and_redirected_to_dashboard(): void
    {
        $user = $this->createJobSeekerUser();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_available_for_work' => false,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/profile', [
            'first_name' => 'Ayesha Updated',
            'last_name' => 'Rahman',
            'phone' => '01711111111',
            'city' => 'Chittagong',
            'country' => 'Bangladesh',
            'professional_title' => 'Senior Developer',
            'professional_summary' => 'Updated summary',
            'current_job_title' => 'Lead Developer',
            'current_company' => 'CareerConnectBD',
            'years_of_experience' => 5,
            'expected_salary' => '120000',
            'preferred_job_type' => 'Remote',
            'preferred_workplace' => 'Hybrid',
            'preferred_location' => 'Dhaka',
            'linkedin_url' => 'https://linkedin.com/in/ayesha',
            'github_url' => 'https://github.com/ayesha',
            'portfolio_url' => 'https://ayesha.dev',
            'website_url' => 'https://ayesha.dev',
            'is_available_for_work' => '1',
        ]);

        $response->assertRedirect('/job-seeker/dashboard');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_seeker_profiles', [
            'id' => $profile->id,
            'first_name' => 'Ayesha Updated',
            'professional_title' => 'Senior Developer',
            'is_available_for_work' => true,
        ]);
    }
}
