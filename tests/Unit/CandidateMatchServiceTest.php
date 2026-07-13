<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Models\Skill;
use App\Services\CandidateMatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateMatchServiceTest extends TestCase
{
    use RefreshDatabase;

    private CandidateMatchService $matchService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->matchService = app(CandidateMatchService::class);
    }

    public function test_calculate_returns_low_score_for_missing_resume_and_skills(): void
    {
        $job = Job::factory()->create(['requirements' => 'Laravel, PHP', 'experience_level' => 'Senior Level', 'education_level' => 'Bachelor']);
        $profile = JobSeekerProfile::factory()->create(['years_of_experience' => 0]);

        $score = $this->matchService->calculate($job, $profile);

        $this->assertIsInt($score);
        $this->assertLessThanOrEqual(10, $score);
    }

    public function test_matched_and_missing_skills_are_calculated(): void
    {
        $job = Job::factory()->create(['requirements' => 'Laravel, PHP, Docker', 'experience_level' => 'Mid Level', 'education_level' => 'Bachelor']);
        $profile = JobSeekerProfile::factory()->create(['years_of_experience' => 5]);
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Laravel']);
        Skill::create(['job_seeker_profile_id' => $profile->id, 'skill_name' => 'Symfony']);

        $matched = $this->matchService->matchedSkills($job, $profile);
        $missing = $this->matchService->missingSkills($job, $profile);

        $this->assertEquals(['laravel'], $matched->all());
        $this->assertEquals(['php', 'docker'], $missing->all());
    }

    public function test_profile_strength_uses_profile_completion(): void
    {
        $profile = JobSeekerProfile::factory()->create(['first_name' => 'A', 'last_name' => 'B', 'phone' => '123', 'date_of_birth' => '1990-01-01', 'gender' => 'Male', 'city' => 'Dhaka', 'country' => 'Bangladesh', 'years_of_experience' => 2]);

        $strength = $this->matchService->profileStrength($profile);

        $this->assertIsInt($strength);
        $this->assertGreaterThanOrEqual(0, $strength);
        $this->assertLessThanOrEqual(100, $strength);
    }

    public function test_resume_uploaded_contributes_to_score(): void
    {
        $job = Job::factory()->create(['requirements' => 'Laravel, PHP', 'experience_level' => 'Entry Level', 'education_level' => 'Bachelor']);
        $profile = JobSeekerProfile::factory()->create(['years_of_experience' => 1]);
        Resume::create([
            'job_seeker_profile_id' => $profile->id,
            'title' => 'Test Resume',
            'file_path' => 'resumes/test.pdf',
            'file_name' => 'test.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
        ]);

        $score = $this->matchService->calculate($job, $profile);

        $this->assertGreaterThan(0, $score);
    }
}
