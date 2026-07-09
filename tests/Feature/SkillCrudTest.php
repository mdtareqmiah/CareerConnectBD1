<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillCrudTest extends TestCase
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

    public function test_job_seeker_can_view_the_skills_list_page(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->get('/job-seeker/skills');

        $response->assertStatus(200);
        $response->assertSee('Skills');
    }

    public function test_job_seeker_can_create_a_skill(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/skills', [
            'skill_name' => 'Laravel',
            'proficiency_level' => 'Advanced',
            'years_of_experience' => '3',
            'notes' => 'Built APIs.',
        ]);

        $response->assertRedirect('/job-seeker/skills');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('skills', [
            'job_seeker_profile_id' => $profile->id,
            'skill_name' => 'Laravel',
            'proficiency_level' => 'Advanced',
        ]);
    }

    public function test_skill_creation_requires_valid_data(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/skills', [
            'skill_name' => '',
            'proficiency_level' => 'Invalid',
            'years_of_experience' => '80',
        ]);

        $response->assertSessionHasErrors(['skill_name', 'proficiency_level', 'years_of_experience']);
    }

    public function test_job_seeker_can_update_a_skill(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $skill = Skill::create([
            'job_seeker_profile_id' => $profile->id,
            'skill_name' => 'PHP',
            'proficiency_level' => 'Beginner',
            'years_of_experience' => 1,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/skills/'.$skill->id, [
            'skill_name' => 'PHP 8',
            'proficiency_level' => 'Expert',
            'years_of_experience' => '5',
            'notes' => 'Updated notes.',
        ]);

        $response->assertRedirect('/job-seeker/skills');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'skill_name' => 'PHP 8',
            'proficiency_level' => 'Expert',
        ]);
    }

    public function test_job_seeker_can_delete_a_skill(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $skill = Skill::create([
            'job_seeker_profile_id' => $profile->id,
            'skill_name' => 'Vue',
            'proficiency_level' => 'Intermediate',
        ]);

        $response = $this->actingAs($user)->delete('/job-seeker/skills/'.$skill->id);

        $response->assertRedirect('/job-seeker/skills');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_non_job_seeker_users_cannot_access_skills_routes(): void
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

        $response = $this->actingAs($user)->get('/job-seeker/skills');

        $response->assertStatus(403);
    }

    public function test_dashboard_shows_skills_count_and_completion_updates(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Skills');
        $response->assertSee('0');
        $response->assertSee('Manage Skills');
        $response->assertSee(route('job-seeker.skills.index'));

        Skill::create([
            'job_seeker_profile_id' => $profile->id,
            'skill_name' => 'Laravel',
            'proficiency_level' => 'Advanced',
        ]);

        $response = $this->actingAs($user)->get('/job-seeker/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1');
    }
}
