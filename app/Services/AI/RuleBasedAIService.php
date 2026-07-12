<?php

namespace App\Services\AI;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use App\Services\CandidateMatchService;
use App\Services\ResumeAnalyzerService;

class RuleBasedAIService implements AIServiceInterface
{
    public function __construct(
        private CandidateMatchService $candidateMatchService,
        private ResumeAnalyzerService $resumeAnalyzerService
    ) {
    }

    public function calculateCandidateMatch(Job $job, JobSeekerProfile $profile): int
    {
        return $this->candidateMatchService->calculate($job, $profile);
    }

    public function reviewResume(Resume $resume): array
    {
        return $this->resumeAnalyzerService->analyze($resume)->toArray();
    }

    public function generateCoverLetter(Job $job, JobSeekerProfile $profile): string
    {
        return 'Cover letter generation is not available for rule-based provider.';
    }

    public function generateInterviewQuestions(Job $job, JobSeekerProfile $profile): array
    {
        return [];
    }

    public function recommendJobs(Job $job, JobSeekerProfile $profile): array
    {
        return [];
    }
}
