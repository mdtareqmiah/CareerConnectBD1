<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Experience;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeAnalysisFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_resume_index_shows_analysis_for_uploaded_resume(): void
    {
        $role = Role::firstOrCreate(['slug' => 'job-seeker'], ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $profile = JobSeekerProfile::factory()->create(['user_id' => $user->id]);

        Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Test Resume',
            'file_path' => 'resumes/test.pdf',
            'file_type' => 'pdf',
            'file_size' => 102400,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('job-seeker.resumes.index'));

        $response->assertStatus(200);
        $response->assertSee('Resume Analysis');
        $response->assertSee('Score:');
        $response->assertSee('Strengths');
        $response->assertSee('Suggestions');
    }
}
