<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobSeekerProfile;
use App\Models\Skill;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CandidateMatchService
{
    public function calculate(Job $job, JobSeekerProfile $profile): int
    {
        $weights = config('candidate_match.weights');

        $skillsScore = $this->calculateSkillsScore($job, $profile) * ($weights['skills'] / 100);
        $experienceScore = $this->calculateExperienceScore($job, $profile) * ($weights['experience'] / 100);
        $educationScore = $this->calculateEducationScore($job, $profile) * ($weights['education'] / 100);
        $resumeScore = $this->calculateResumeScore($profile) * ($weights['resume'] / 100);
        $profileScore = $this->calculateProfileScore($profile) * ($weights['profile'] / 100);

        return (int) round($skillsScore + $experienceScore + $educationScore + $resumeScore + $profileScore);
    }

    public function matchedSkills(Job $job, JobSeekerProfile $profile): Collection
    {
        $jobSkills = $this->normalizeJobSkills($job);
        $profileSkills = $this->normalizeProfileSkills($profile);

        return $profileSkills->intersect($jobSkills)->values();
    }

    public function missingSkills(Job $job, JobSeekerProfile $profile): Collection
    {
        $jobSkills = $this->normalizeJobSkills($job);
        $profileSkills = $this->normalizeProfileSkills($profile);

        return $jobSkills->diff($profileSkills)->values();
    }

    public function profileStrength(JobSeekerProfile $profile): int
    {
        return $this->calculateProfileScore($profile);
    }

    private function calculateSkillsScore(Job $job, JobSeekerProfile $profile): int
    {
        $jobSkills = $this->normalizeJobSkills($job);
        $profileSkills = $this->normalizeProfileSkills($profile);

        if ($jobSkills->isEmpty()) {
            return 100;
        }

        return (int) round($profileSkills->intersect($jobSkills)->count() / $jobSkills->count() * 100);
    }

    private function calculateExperienceScore(Job $job, JobSeekerProfile $profile): int
    {
        $requiredLevel = strtolower($job->experience_level ?: 'entry level');
        $profileYears = (int) $profile->years_of_experience;

        return match ($requiredLevel) {
            'senior level' => $this->experienceScoreFromYears($profileYears, 8),
            'mid level' => $this->experienceScoreFromYears($profileYears, 4),
            'entry level' => $this->experienceScoreFromYears($profileYears, 1),
            default => $this->experienceScoreFromYears($profileYears, 2),
        };
    }

    private function calculateEducationScore(Job $job, JobSeekerProfile $profile): int
    {
        if (empty($job->education_level)) {
            return 100;
        }

        $profileEducation = $profile->educations()->pluck('education_level')->map(fn ($value) => strtolower($value))->unique();
        $required = strtolower($job->education_level);

        if ($profileEducation->contains($required)) {
            return 100;
        }

        $ordered = ['high school', 'diploma', 'bachelor', 'master', 'doctorate'];
        $requiredIndex = array_search($required, $ordered, true);

        if ($requiredIndex === false) {
            return $profileEducation->isNotEmpty() ? 75 : 0;
        }

        foreach ($profileEducation as $level) {
            $profileIndex = array_search($level, $ordered, true);

            if ($profileIndex !== false && $profileIndex >= $requiredIndex) {
                return 100;
            }
        }

        return $profileEducation->isNotEmpty() ? 50 : 0;
    }

    private function calculateResumeScore(JobSeekerProfile $profile): int
    {
        return $profile->resumes()->whereNotNull('file_path')->exists() ? 100 : 0;
    }

    private function calculateProfileScore(JobSeekerProfile $profile): int
    {
        $completionService = app(ProfileCompletionService::class);

        return $completionService->getCompletionPercentage($profile);
    }

    private function experienceScoreFromYears(int $years, int $target): int
    {
        if ($years <= 0) {
            return 0;
        }

        return min(100, (int) round($years / $target * 100));
    }

    private function normalizeJobSkills(Job $job): Collection
    {
        $skills = collect();

        if ($job->requirements) {
            $skills = $skills->merge($this->extractSkillsFromText($job->requirements));
        }

        return $skills->unique()->values();
    }

    private function normalizeProfileSkills(JobSeekerProfile $profile): Collection
    {
        return $profile->skills
            ->pluck('skill_name')
            ->filter()
            ->map(fn ($skill) => strtolower(trim($skill)))
            ->unique()
            ->values();
    }

    private function extractSkillsFromText(?string $text): array
    {
        if (empty($text)) {
            return [];
        }

        $terms = preg_split('/[\r\n,;\.\/\(\)]+/', $text);

        return collect($terms)
            ->map(fn ($term) => strtolower(trim($term)))
            ->filter()
            ->map(fn ($term) => preg_replace('/[^a-z0-9\+\#\- ]/', '', $term))
            ->filter()
            ->map(fn ($term) => preg_replace('/\s+/', ' ', $term))
            ->filter(fn ($term) => strlen($term) > 1)
            ->unique()
            ->values()
            ->all();
    }
}
