<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\User;
use App\Services\ProfileCompletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCompletionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_completion_details_for_a_job_seeker_profile(): void
    {
        $user = User::factory()->create();

        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'professional_title' => 'Software Engineer',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => 'Experienced backend developer.',
            'profile_photo' => 'profiles/demo.jpg',
        ]);

        $profile->educations()->create([
            'degree' => 'BSc',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'BUET',
            'education_level' => 'Bachelor',
            'passing_year' => 2020,
            'start_date' => '2016-01-01',
            'end_date' => '2020-01-01',
            'is_current' => false,
            'description' => 'Graduated with honors.',
        ]);

        $profile->experiences()->create([
            'job_title' => 'Backend Developer',
            'company_name' => 'Tech Ltd',
            'employment_type' => 'Full-time',
            'start_date' => '2020-02-01',
            'currently_working' => true,
            'job_description' => 'Built APIs.',
        ]);

        $skill = Skill::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'category' => 'Backend',
            'is_active' => true,
        ]);

        $profile->skills()->attach($skill->id, [
            'proficiency_level' => 'Advanced',
            'years_of_experience' => 3,
        ]);

        $profile->resumes()->create([
            'title' => 'Main Resume',
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
            'file_type' => 'pdf',
            'file_size' => 2048,
            'is_default' => true,
            'is_active' => true,
            'uploaded_at' => now(),
        ]);

        $service = app(ProfileCompletionService::class);
        $result = $service->getCompletionDetails($profile);

        $this->assertSame(100, $result['percentage']);
        $this->assertSame(['basic_profile', 'professional_summary', 'education', 'experience', 'skills', 'resume', 'profile_photo'], $result['completed_sections']);
        $this->assertSame([], $result['missing_sections']);
    }
}
