<?php

namespace App\Services\AI;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Resume;

interface AIServiceInterface
{
    public function calculateCandidateMatch(Job $job, JobSeekerProfile $profile): int;

    public function reviewResume(Resume $resume): array;

    public function generateCoverLetter(Job $job, JobSeekerProfile $profile): string;

    public function generateInterviewQuestions(Job $job, JobSeekerProfile $profile): array;

    public function recommendJobs(Job $job, JobSeekerProfile $profile): array;
}
