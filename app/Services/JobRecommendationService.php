<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use Illuminate\Support\Collection;


class JobRecommendationService
{
    private const CACHE_TTL_MINUTES = 10;

    public function __construct(private CandidateMatchService $candidateMatchService)
    {
    }

    public function recommendForProfile(JobSeekerProfile $profile, int $limit = 5): Collection
    {
        if (! $profile->user) {
            return collect();
        }

        return $this->buildRecommendations($profile, $limit);
    }

    public function recommendForJob(Job $job, JobSeekerProfile $profile): array
    {
        return $this->transformRecommendation($job, $profile);
    }

    protected function buildRecommendations(JobSeekerProfile $profile, int $limit): Collection
    {
        $defaultResumeExists = $profile->resumes()->whereNotNull('file_path')->exists();
        $preferredLocation = strtolower((string) $profile->preferred_location);
        $preferredJobType = strtolower((string) $profile->preferred_job_type);
        $expectedSalary = $profile->expected_salary;

        $jobs = Job::with('company')
            ->where('status', 'published')
            ->whereDate('deadline', '>=', today())
            ->get();

        return $jobs
            ->map(fn (Job $job) => $this->transformRecommendation($job, $profile, $defaultResumeExists, $preferredLocation, $preferredJobType, $expectedSalary))
            ->filter(fn (array $recommendation) => $recommendation['score'] > 0)
            ->sortByDesc('score')
            ->values()
            ->take($limit);
    }

    protected function transformRecommendation(Job $job, JobSeekerProfile $profile, bool $defaultResumeExists = false, string $preferredLocation = '', string $preferredJobType = '', ?float $expectedSalary = null): array
    {
        $skillsScore = $this->candidateMatchService->calculateSkillsScore($job, $profile);
        $experienceScore = $this->candidateMatchService->calculateExperienceScore($job, $profile);
        $educationScore = $this->candidateMatchService->calculateEducationScore($job, $profile);
        $resumeScore = $defaultResumeExists ? 100 : 0;
        $profileScore = app(ProfileCompletionService::class)->getCompletionPercentage($profile);

        $locationScore = $this->calculateLocationScore($job->location, $preferredLocation);
        $jobTypeScore = $this->calculateEmploymentTypeScore($job->job_type, $preferredJobType);
        $salaryScore = $this->calculateSalaryScore($job, $expectedSalary);
        $recentActivityScore = $this->calculateRecentActivityScore($profile);

        $score = $this->calculateRecommendationScore(
            $skillsScore,
            $experienceScore,
            $educationScore,
            $resumeScore,
            $profileScore,
            $locationScore,
            $jobTypeScore,
            $salaryScore,
            $recentActivityScore
        );

        return [
            'job' => $job,
            'company' => $job->company,
            'score' => $score,
            'matched_skills' => $this->candidateMatchService->matchedSkills($job, $profile),
            'missing_skills' => $this->candidateMatchService->missingSkills($job, $profile),
            'reason' => $this->buildRecommendationReason($score, $skillsScore, $experienceScore, $educationScore, $locationScore, $jobTypeScore, $salaryScore, $resumeScore),
            'level' => $this->mapRecommendationLevel($score),
        ];
    }

    protected function calculateRecommendationScore(int $skillsScore, int $experienceScore, int $educationScore, int $resumeScore, int $profileScore, int $locationScore, int $jobTypeScore, int $salaryScore, int $recentActivityScore): int
    {
        $weights = [
            'skills' => 25,
            'experience' => 20,
            'education' => 15,
            'resume' => 10,
            'profile' => 10,
            'location' => 8,
            'employment_type' => 6,
            'salary' => 4,
            'recent_activity' => 2,
        ];

        $total = 0;
        $total += $skillsScore * ($weights['skills'] / 100);
        $total += $experienceScore * ($weights['experience'] / 100);
        $total += $educationScore * ($weights['education'] / 100);
        $total += $resumeScore * ($weights['resume'] / 100);
        $total += $profileScore * ($weights['profile'] / 100);
        $total += $locationScore * ($weights['location'] / 100);
        $total += $jobTypeScore * ($weights['employment_type'] / 100);
        $total += $salaryScore * ($weights['salary'] / 100);
        $total += $recentActivityScore * ($weights['recent_activity'] / 100);

        return max(0, min(100, (int) round($total)));
    }

    protected function calculateLocationScore(?string $jobLocation, string $preferredLocation): int
    {
        if (empty($preferredLocation) || empty($jobLocation)) {
            return 50;
        }

        $jobLocation = strtolower($jobLocation);

        if (str_contains($jobLocation, $preferredLocation) || str_contains($preferredLocation, $jobLocation)) {
            return 100;
        }

        return 50;
    }

    protected function calculateEmploymentTypeScore(?string $jobType, string $preferredJobType): int
    {
        if (empty($preferredJobType) || empty($jobType)) {
            return 50;
        }

        if (strtolower($jobType) === $preferredJobType) {
            return 100;
        }

        return 50;
    }

    protected function calculateSalaryScore(Job $job, ?float $expectedSalary): int
    {
        if ($expectedSalary === null || $expectedSalary <= 0 || $job->salary_min === null || $job->salary_max === null) {
            return 50;
        }

        if ($expectedSalary >= $job->salary_min && $expectedSalary <= $job->salary_max) {
            return 100;
        }

        if ($expectedSalary < $job->salary_min) {
            return 75;
        }

        return 50;
    }

    protected function calculateRecentActivityScore(JobSeekerProfile $profile): int
    {
        $lastUpdatedAt = collect([
            $profile->updated_at,
            $profile->educations()->latest('updated_at')->value('updated_at'),
            $profile->experiences()->latest('updated_at')->value('updated_at'),
            $profile->skills()->latest('updated_at')->value('updated_at'),
            $profile->resumes()->latest('updated_at')->value('updated_at'),
        ])->filter()->max();

        if (! $lastUpdatedAt) {
            return 0;
        }

        $days = now()->diffInDays($lastUpdatedAt);

        return match (true) {
            $days <= 7 => 100,
            $days <= 30 => 80,
            $days <= 60 => 60,
            $days <= 90 => 40,
            default => 20,
        };
    }

    protected function buildRecommendationReason(int $score, int $skillsScore, int $experienceScore, int $educationScore, int $locationScore, int $jobTypeScore, int $salaryScore, int $resumeScore): string
    {
        $parts = [];

        if ($skillsScore >= 70) {
            $parts[] = 'Strong skills match';
        }

        if ($experienceScore >= 70) {
            $parts[] = 'Good experience alignment';
        }

        if ($educationScore >= 70) {
            $parts[] = 'Education requirement met';
        }

        if ($locationScore >= 75) {
            $parts[] = 'Location preference aligned';
        }

        if ($jobTypeScore >= 75) {
            $parts[] = 'Employment type fits your preference';
        }

        if ($salaryScore >= 75) {
            $parts[] = 'Salary range matches expectations';
        }

        if ($resumeScore === 100) {
            $parts[] = 'Resume available for quick application';
        }

        if (empty($parts)) {
            return 'Recommended based on your profile and job details.';
        }

        return implode('. ', $parts) . '.';
    }

    protected function mapRecommendationLevel(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 75 => 'Highly Recommended',
            $score >= 60 => 'Recommended',
            $score >= 40 => 'Average',
            default => 'Low Match',
        };
    }
}
