<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobSeekerProfileCreateTest extends TestCase
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

    public function test_job_seeker_can_open_the_profile_creation_form(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->get('/job-seeker/profile/create');

        $response->assertStatus(200);
        $response->assertSee('Create Your Profile');
    }

    public function test_job_seeker_profile_creation_requires_valid_data(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->post('/job-seeker/profile', [
            'first_name' => '',
            'last_name' => '',
            'phone' => 'abc',
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'phone']);
    }

    public function test_job_seeker_profile_can_be_created_and_redirected_to_dashboard(): void
    {
        $user = $this->createJobSeekerUser();

        $response = $this->actingAs($user)->post('/job-seeker/profile', [
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_title' => 'Backend Developer',
            'professional_summary' => 'Experienced backend developer.',
            'is_available_for_work' => '1',
        ]);

        $response->assertRedirect('/job-seeker/dashboard');
        $this->assertDatabaseHas('job_seeker_profiles', ['user_id' => $user->id, 'first_name' => 'Ayesha']);
    }

    public function test_job_seeker_profile_photo_can_be_uploaded_during_profile_creation(): void
    {
        Storage::fake('public');
        $user = $this->createJobSeekerUser();
        $photo = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/job-seeker/profile', [
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'professional_title' => 'Backend Developer',
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect('/job-seeker/dashboard');

        $profile = JobSeekerProfile::first();
        $this->assertNotNull($profile->profile_photo);
        Storage::disk('public')->assertExists($profile->profile_photo);
    }

    public function test_existing_profile_redirects_to_edit_profile_route(): void
    {
        $user = $this->createJobSeekerUser();
        JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/profile/create');

        $response->assertRedirect('/job-seeker/profile/edit');
    }
}
