<?php

namespace Tests\Feature;

use App\Models\JobSeekerProfile;
use App\Models\User;
use App\Services\ProfileCompletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerProfileCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_completion_reports_zero_when_profile_is_empty(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => '',
            'last_name' => '',
            'phone' => null,
            'date_of_birth' => null,
            'gender' => null,
            'city' => null,
            'country' => null,
            'professional_summary' => null,
            'profile_photo' => null,
        ]);

        $service = app(ProfileCompletionService::class);
        $result = $service->getCompletionDetails($profile);

        $this->assertSame(0, $result['percentage']);
        $this->assertSame([], $result['completed_sections']);
        $this->assertCount(7, $result['missing_sections']);
    }

    public function test_profile_completion_reports_partial_when_only_basic_information_is_present(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => null,
            'profile_photo' => null,
        ]);

        $service = app(ProfileCompletionService::class);
        $result = $service->getCompletionDetails($profile);

        $this->assertGreaterThan(0, $result['percentage']);
        $this->assertContains('basic_profile', $result['completed_sections']);
        $this->assertContains('professional_summary', $result['missing_sections']);
    }

    public function test_profile_completion_reaches_full_percentage_when_all_sections_are_done(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ayesha',
            'last_name' => 'Rahman',
            'phone' => '01700000000',
            'date_of_birth' => '1998-05-10',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => 'Experienced backend developer.',
            'profile_photo' => 'profiles/demo.jpg',
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
        $skill = new \App\Models\Skill([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'category' => 'Backend',
            'is_active' => true,
        ]);
        $skill->save();
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
