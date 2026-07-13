<?php

namespace App\Services;

class ResumeAnalysisResult
{
    public function __construct(
        public int $score,
        public array $strengths,
        public array $warnings,
        public array $suggestions,
        public array $missingSections,
        public string $readiness,
        public string $lastAnalyzed,
        public int $keywordCount,
        public string $estimatedATS,
        public int $atsScore,
        public ?int $resumeAgeDays
    ) {
    }

    public function toArray(): array
    {
        return [
            'resume_score' => $this->score,
            'strengths' => $this->strengths,
            'warnings' => $this->warnings,
            'suggestions' => $this->suggestions,
            'missingSections' => $this->missingSections,
            'readiness' => $this->readiness,
            'lastAnalyzed' => $this->lastAnalyzed,
            'keywordCount' => $this->keywordCount,
            'estimatedATS' => $this->estimatedATS,
            'atsScore' => $this->atsScore,
            'resumeAgeDays' => $this->resumeAgeDays,
        ];
    }
}
