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

class DashboardPolishTest extends TestCase
{
    use RefreshDatabase;

    private function createProfileWithData(): array
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
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => 'Experienced developer.',
            'professional_title' => 'Backend Developer',
            'current_job_title' => 'Backend Developer',
            'current_company' => 'Tech Ltd',
            'profile_photo' => 'profiles/demo.jpg',
            'is_available_for_work' => true,
        ]);

        $profile->educations()->create([
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'BUET',
            'passing_year' => 2020,
        ]);

        $profile->experiences()->create([
            'job_title' => 'Backend Developer',
            'company_name' => 'Tech Ltd',
            'employment_type' => 'Full-time',
            'start_date' => '2020-02-01',
            'currently_working' => true,
            'job_description' => 'Built APIs.',
        ]);

        $profile->skills()->create([
            'skill_name' => 'Laravel',
            'proficiency_level' => 'Advanced',
            'years_of_experience' => 3,
        ]);

        $profile->resumes()->create([
            'title' => 'Main Resume',
            'file_path' => 'resumes/main.pdf',
            'file_name' => 'main.pdf',
            'file_path' => 'resumes/main.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
            'is_default' => true,
        ]);

        return [$user, $profile];
    }

    public function test_dashboard_loads_and_shows_polished_sections(): void
    {
        [$user] = $this->createProfileWithData();

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Statistics');
        $response->assertSee('Quick Actions');
        $response->assertSee('Recent Activity');
        $response->assertSee('Profile Completion');
        $response->assertSee('Manage Profile');
    }

    public function test_dashboard_shows_correct_counts_and_completion_details(): void
    {
        [$user, $profile] = $this->createProfileWithData();

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertStatus(200);
        $response->assertSee('1');
        $response->assertSee('Education');
        $response->assertSee('Experience');
        $response->assertSee('Skills');
        $response->assertSee('Resumes');
        $response->assertSee('100%');
        $response->assertSee('Backend Developer');
        $response->assertSee('Tech Ltd');
    }

    public function test_dashboard_shows_empty_state_when_profile_has_no_content(): void
    {
        $role = Role::firstOrCreate(
            ['slug' => 'job-seeker'],
            ['name' => 'Job Seeker', 'description' => 'Job Seeker', 'is_active' => true]
        );
        $user = User::factory()->create(['role_id' => $role->id]);
        JobSeekerProfile::create(['user_id' => $user->id, 'first_name' => 'A', 'last_name' => 'B', 'phone' => '1']);

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');

        $response->assertStatus(200);
        $response->assertSee('No education added yet');
        $response->assertSee('No experience added yet');
        $response->assertSee('No skills added yet');
        $response->assertSee('No resumes uploaded yet');
    }
}
