<?php

namespace Tests\Unit;

use App\Models\Education;
use App\Models\Experience;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Skill;
use App\Services\ResumeAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resume_analysis_returns_score_and_strengths(): void
    {
        $profile = JobSeekerProfile::factory()->create();
        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Test Resume',
            'file_path' => 'resumes/test.pdf',
            'file_type' => 'pdf',
            'file_size' => 102400,
        ]);

        Education::create([
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'Bachelor',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'Test University',
            'board_or_university' => 'Test Board',
            'education_level' => 'Bachelor',
            'result' => 'A',
            'grading_system' => '4.0',
            'passing_year' => 2020,
        ]);
        Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'Test Company',
            'job_title' => 'Developer',
            'employment_type' => 'Full-time',
            'location' => 'Dhaka',
            'start_date' => '2020-01-01',
            'end_date' => '2022-01-01',
            'currently_working' => false,
            'job_description' => 'Developed applications',
        ]);
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);

        $service = $this->app->make(ResumeAnalysisService::class);
        $analysis = $service->analyze($resume);

        $this->assertArrayHasKey('resume_score', $analysis);
        $this->assertArrayHasKey('atsScore', $analysis);
        $this->assertArrayHasKey('strengths', $analysis);
        $this->assertArrayHasKey('suggestions', $analysis);
        $this->assertGreaterThanOrEqual(0, $analysis['atsScore']);
        $this->assertGreaterThan(0, $analysis['resume_score']);
        $this->assertContains('Resume uploaded', $analysis['strengths']);
    }

    public function test_empty_profile_analysis_provides_suggestions(): void
    {
        $profile = JobSeekerProfile::factory()->create();
        $resume = Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Empty Resume',
            'file_path' => 'resumes/empty.pdf',
            'file_type' => 'pdf',
            'file_size' => 102400,
        ]);

        $service = $this->app->make(ResumeAnalysisService::class);
        $analysis = $service->analyze($resume);

        $this->assertLessThanOrEqual(100, $analysis['resume_score']);
        $this->assertNotEmpty($analysis['suggestions']);
        $this->assertContains('Add more education to your profile.', $analysis['suggestions']);
    }

    public function test_complete_profile_analysis_returns_high_score(): void
    {
        $profile = JobSeekerProfile::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '01700000000',
            'date_of_birth' => '1990-01-01',
            'gender' => 'Female',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'professional_summary' => 'Experienced developer',
        ]);

        Education::create([
            'job_seeker_profile_id' => $profile->id,
            'degree' => 'Bachelor',
            'field_of_study' => 'Computer Science',
            'institution_name' => 'Test University',
            'board_or_university' => 'Test Board',
            'education_level' => 'Bachelor',
            'result' => 'A',
            'grading_system' => '4.0',
            'passing_year' => 2020,
        ]);
        Experience::create([
            'job_seeker_profile_id' => $profile->id,
            'company_name' => 'Test Company',
            'job_title' => 'Developer',
            'employment_type' => 'Full-time',
            'location' => 'Dhaka',
            'start_date' => '2020-01-01',
            'end_date' => '2022-01-01',
            'currently_working' => false,
            'job_description' => 'Developed applications',
        ]);
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'PHP']);
        Resume::factory()->create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Complete Resume',
            'file_path' => 'resumes/complete.pdf',
            'file_type' => 'pdf',
            'file_size' => 102400,
        ]);

        $service = $this->app->make(ResumeAnalysisService::class);
        $analysis = $service->analyze($profile->resumes()->first());

        $this->assertGreaterThanOrEqual(70, $analysis['resume_score']);
        $this->assertContains('Good profile completion', $analysis['strengths']);
        $this->assertEmpty($analysis['suggestions']);
    }
}
