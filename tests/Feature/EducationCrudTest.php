<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EducationCrudTest extends TestCase
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

    public function test_job_seeker_can_view_the_education_list_page(): void
    {
        [$user] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->get('/job-seeker/educations');

        $response->assertStatus(200);
        $response->assertSee('Education');
    }

    public function test_job_seeker_can_create_education(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();

        $response = $this->actingAs($user)->post('/job-seeker/educations', [
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'University of Dhaka',
            'board_or_university' => 'University of Dhaka',
            'education_level' => 'Undergraduate',
            'result' => '3.80 CGPA',
            'passing_year' => '2022',
            'start_date' => '2018-01-01',
            'end_date' => '2022-12-31',
            'is_current' => '0',
            'description' => 'Completed undergraduate studies.',
        ]);

        $response->assertRedirect('/job-seeker/educations');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('educations', [
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'BSc',
            'institution_name' => 'University of Dhaka',
        ]);
    }

    public function test_job_seeker_can_update_education(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $education = Education::create([
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'University of Dhaka',
            'result' => '3.50',
            'passing_year' => '2021',
            'is_current' => false,
        ]);

        $response = $this->actingAs($user)->patch('/job-seeker/educations/'.$education->id, [
            'degree' => 'MSc',
            'field_of_study' => 'Software Engineering',
            'institution_name' => 'BUET',
            'board_or_university' => 'BUET',
            'education_level' => 'Graduate',
            'result' => '3.90 CGPA',
            'passing_year' => '2024',
            'start_date' => '2022-01-01',
            'end_date' => '2024-12-31',
            'is_current' => '0',
            'description' => 'Completed master studies.',
        ]);

        $response->assertRedirect('/job-seeker/educations');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('educations', [
            'id' => $education->id,
            'degree' => 'MSc',
            'institution_name' => 'BUET',
        ]);
    }

    public function test_job_seeker_can_delete_education(): void
    {
        [$user, $profile] = $this->createJobSeekerUserWithProfile();
        $education = Education::create([
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'University of Dhaka',
            'result' => '3.50',
            'passing_year' => '2021',
            'is_current' => false,
        ]);

        $response = $this->actingAs($user)->delete('/job-seeker/educations/'.$education->id);

        $response->assertRedirect('/job-seeker/educations');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('educations', ['id' => $education->id]);
    }
}
