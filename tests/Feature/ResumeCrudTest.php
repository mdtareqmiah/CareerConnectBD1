<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResumeCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeekerUserWithProfile(): array
    {
        $role = Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]
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

    public function test_job_seeker_can_upload_a_resume(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/resumes', [
            'title' => 'Main Resume',
            'file_path' => UploadedFile::fake()->create('resume.pdf', 2048, 'application/pdf'),
        ]);

        $response->assertRedirect('/job-seeker/resumes');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('resumes', ['job_seeker_profile_id' => $profile->id, 'title' => 'Main Resume']);
        Storage::disk('public')->assertExists('resumes');
    }

    public function test_resume_creation_requires_valid_data(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/resumes', [
            'title' => '',
            'file_path' => UploadedFile::fake()->create('resume.txt', 2048, 'text/plain'),
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_job_seeker_can_edit_and_set_default_resume(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $resume = Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Old Resume',
            'file_path' => 'resumes/old.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/resumes/'.$resume->id, [
            'title' => 'Updated Resume',
            'is_default' => '1',
        ]);

        $response->assertRedirect('/job-seeker/resumes');
        $this->assertDatabaseHas('resumes', ['id' => $resume->id, 'title' => 'Updated Resume', 'is_default' => true]);
    }

    public function test_job_seeker_can_delete_a_resume(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $resume = Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Delete Me',
            'file_path' => 'resumes/delete-me.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
        ]);

        $response = $this->actingAs($user)->delete('/job-seeker/resumes/'.$resume->id);

        $response->assertRedirect('/job-seeker/resumes');
        $this->assertDatabaseMissing('resumes', ['id' => $resume->id]);
    }

    public function test_job_seeker_can_download_a_resume(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $resume = Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Download Me',
            'file_path' => 'resumes/download-me.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
        ]);
        Storage::disk('public')->put('resumes/download-me.pdf', 'content');

        $response = $this->actingAs($user)->get('/job-seeker/resumes/'.$resume->id.'/download');

        $response->assertOk();
        $response->assertDownload('download-me.pdf');
    }

    public function test_non_job_seeker_users_cannot_access_resume_routes(): void
    {
        $role = Role::firstOrCreate(
            ['slug' => 'employer'],
            ['name' => 'Employer', 'description' => 'Employer', 'is_active' => true]
        );
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/job-seeker/resumes');

        $response->assertStatus(403);
    }

    public function test_dashboard_shows_resume_count_and_default_resume_summary(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Resumes');
        $response->assertSee('Manage Resume');

        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Primary Resume',
            'file_path' => 'resumes/primary.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1');
    }
}
