<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Skill;
use App\Services\JobRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    private JobRecommendationService $recommendationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recommendationService = app(JobRecommendationService::class);
    }

    public function test_recommend_for_profile_returns_collection_of_job_recommendations(): void
    {
        $profile = JobSeekerProfile::factory()->create([
            'expected_salary' => 50000,
            'preferred_job_type' => 'Full-time',
            'preferred_location' => 'Dhaka',
            'years_of_experience' => 3,
        ]);

        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);
        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Resume',
            'file_path' => 'resumes/resume.pdf',
            'file_name' => 'resume.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
        ]);

        Job::factory()->create([
            'title' => 'Laravel Developer',
            'requirements' => 'Laravel, PHP',
            'job_type' => 'Full-time',
            'location' => 'Dhaka',
            'salary_type' => 'Monthly',
            'salary_min' => 40000,
            'salary_max' => 60000,
            'experience_level' => 'Mid Level',
            'education_level' => 'Bachelor',
            'status' => 'published',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $recommendations = $this->recommendationService->recommendForProfile($profile, 5);

        $this->assertNotEmpty($recommendations);
        $this->assertTrue($recommendations->first()['score'] >= 0);
        $this->assertArrayHasKey('job', $recommendations->first());
        $this->assertArrayHasKey('reason', $recommendations->first());
    }

    public function test_recommend_for_job_returns_job_recommendation_details(): void
    {
        $profile = JobSeekerProfile::factory()->create([
            'expected_salary' => 50000,
            'preferred_job_type' => 'Full-time',
            'preferred_location' => 'Dhaka',
            'years_of_experience' => 3,
        ]);

        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);
        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Resume',
            'file_path' => 'resumes/resume.pdf',
            'file_name' => 'resume.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
        ]);

        $job = Job::factory()->create([
            'title' => 'Laravel Developer',
            'requirements' => 'Laravel, PHP',
            'job_type' => 'Full-time',
            'location' => 'Dhaka',
            'salary_type' => 'Monthly',
            'salary_min' => 40000,
            'salary_max' => 60000,
            'experience_level' => 'Mid Level',
            'education_level' => 'Bachelor',
            'status' => 'published',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $recommendation = $this->recommendationService->recommendForJob($job, $profile);

        $this->assertIsArray($recommendation);
        $this->assertArrayHasKey('score', $recommendation);
        $this->assertArrayHasKey('reason', $recommendation);
        $this->assertArrayHasKey('job', $recommendation);
        $this->assertSame($job->id, $recommendation['job']->id);
    }
}
