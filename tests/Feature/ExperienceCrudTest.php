<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeekerUserWithProfile(): array
    {
        $role = Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            [
                'name' => 'Job Seeker',
                'description' => 'Job Seeker',
                'is_active' => true,
            ]
        );

        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
        ]);

        return [$user, $profile];
    }

    public function test_job_seeker_can_view_the_experience_list_page(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->get('/job-seeker/experiences');

        $response->assertStatus(200);
        $response->assertSee('Experience');
    }

    public function test_job_seeker_can_create_experience(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/experiences', [
            'company_name' => 'Acme Ltd',
            'job_title' => 'Software Engineer',
            'employment_type' => 'Full-time',
            'location' => 'Dhaka',
            'start_date' => '2022-01-01',
            'end_date' => '2024-12-31',
            'currently_working' => '0',
            'job_description' => 'Built internal tools.',
        ]);

        $response->assertRedirect('/job-seeker/experiences');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('experiences', [
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'Acme Ltd',
            'job_title' => 'Software Engineer',
        ]);
    }

    public function test_job_seeker_can_update_experience(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $experience = Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'Old Company',
            'job_title' => 'Junior Developer',
            'employment_type' => 'Contract',
            'location' => 'Chittagong',
            'start_date' => '2020-01-01',
            'currently_working' => false,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/experiences/'.$experience->id, [
            'company_name' => 'New Company',
            'job_title' => 'Senior Developer',
            'employment_type' => 'Full-time',
            'location' => 'Dhaka',
            'start_date' => '2021-01-01',
            'end_date' => '2024-06-30',
            'currently_working' => '1',
            'job_description' => 'Led the web platform team.',
        ]);

        $response->assertRedirect('/job-seeker/experiences');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'company_name' => 'New Company',
            'job_title' => 'Senior Developer',
        ]);
    }

    public function test_job_seeker_can_delete_experience(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $experience = Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'Temp Company',
            'job_title' => 'Intern',
            'employment_type' => 'Internship',
            'location' => 'Sylhet',
            'start_date' => '2023-01-01',
            'currently_working' => false,
        ]);

        $response = $this->actingAs($user)->delete('/job-seeker/experiences/'.$experience->id);

        $response->assertRedirect('/job-seeker/experiences');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
    }

    public function test_non_job_seeker_users_cannot_access_experience_routes(): void
    {
        $role = Role::firstOrCreate(
            ['slug' => 'employer'],
            [
                'name' => 'Employer',
                'description' => 'Employer',
                'is_active' => true,
            ]
        );

        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/job-seeker/experiences');

        $response->assertStatus(403);
    }

    public function test_job_seeker_dashboard_shows_experience_count_and_manage_button(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'CareerConnectBD',
            'job_title' => 'Product Engineer',
            'employment_type' => 'Full-time',
            'location' => 'Dhaka',
            'start_date' => '2023-01-01',
            'currently_working' => true,
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Experience');
        $response->assertSee('1');
        $response->assertSee('Manage Experience');
        $response->assertSee(route('job-seeker.experiences.index'));
    }
}
