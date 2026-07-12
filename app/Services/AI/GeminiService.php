<?php

namespace App\Services\AI;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;
use LogicException;

class GeminiService implements AIServiceInterface
{
    public function calculateCandidateMatch(Job $job, JobSeekerProfile $profile): int
    {
        throw new LogicException('Gemini provider is not configured.');
    }

    public function reviewResume(Resume $resume): array
    {
        throw new LogicException('Gemini provider is not configured.');
    }

    public function generateCoverLetter(Job $job, JobSeekerProfile $profile): string
    {
        throw new LogicException('Gemini provider is not configured.');
    }

    public function generateInterviewQuestions(Job $job, JobSeekerProfile $profile): array
    {
        throw new LogicException('Gemini provider is not configured.');
    }

    public function recommendJobs(Job $job, JobSeekerProfile $profile): array
    {
        throw new LogicException('Gemini provider is not configured.');
    }
}
