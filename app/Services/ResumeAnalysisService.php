<?php

namespace App\Services;

use App\Models\JobSeekerProfile;
use App\Models\Resume;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ResumeAnalysisService
{
    private const SCORE_WEIGHTS = [
        'uploaded' => 10,
        'title' => 10,
        'default' => 10,
        'profile_completion' => 15,
        'education' => 10,
        'experience' => 10,
        'skills' => 10,
        'valid_type' => 10,
        'valid_size' => 5,
        'recent_upload' => 5,
        'active' => 10,
        'portfolio' => 5,
    ];

    private const VALID_EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MAX_FILE_SIZE = 5242880; // 5 MB
    private const RECENT_DAYS = 90;
    private const OLD_DAYS = 180;

    private array $requiredSections = [
        'summary',
        'experience',
        'education',
        'skills',
        'contact',
    ];

    private array $keywordDictionary = [
        'management', 'leadership', 'project', 'communication', 'team', 'analysis', 'design',
        'development', 'implementation', 'optimization', 'results', 'quality', 'customer',
        'sales', 'marketing', 'budget', 'strategy', 'performance', 'compliance', 'agile',
        'scrum', 'cloud', 'security', 'testing', 'deployment', 'support', 'training',
        'research', 'documentation', 'data', 'analytics', 'machine learning', 'javascript',
        'php', 'python', 'java', 'sql', 'excel', 'problem solving', 'critical thinking',
    ];

    public function analyze(Resume $resume): array
    {
        return Cache::remember("resume_analysis:{$resume->id}", now()->addHours(6), fn () => $this->buildAnalysis($resume)->toArray());
    }

    public function invalidate(Resume $resume): void
    {
        Cache::forget("resume_analysis:{$resume->id}");
    }

    public function invalidateByProfile(JobSeekerProfile $profile): void
    {
        $profile->resumes->each(fn (Resume $resume) => $this->invalidate($resume));
    }

    protected function buildAnalysis(Resume $resume): ResumeAnalysisResult
    {
        $profile = $resume->jobSeekerProfile;
        $completionPercentage = $profile ? app(ProfileCompletionService::class)->getCompletionPercentage($profile) : 0;
        $metadata = $this->extractMetadata($resume);
        $fileAvailable = $metadata['uploaded'] && Storage::disk('public')->exists($resume->file_path);
        $text = $fileAvailable ? trim($this->extractText($resume)) : '';
        $hasText = $text !== '';
        $analysisAvailable = $fileAvailable && $metadata['validType'] && $hasText;

        $sectionsFound = $analysisAvailable ? $this->detectSections($text) : [];
        $missingSections = $analysisAvailable ? array_values(array_diff($this->requiredSections, $sectionsFound)) : $this->requiredSections;
        $keywordCount = $analysisAvailable ? $this->countKeywords($text) : 0;
        $estimatedATS = $this->estimateATSReadiness($keywordCount, $sectionsFound, $metadata['validType']);
        $atsScore = $this->calculateATSScore($analysisAvailable, $metadata, $sectionsFound, $keywordCount, $completionPercentage);
        $resumeAgeDays = $resume->uploaded_at ? $resume->uploaded_at->diffInDays(now()) : null;
        $hasPortfolioLinks = $this->hasPortfolioLinks($profile);

        $strengths = $this->buildStrengths($metadata, $completionPercentage, $sectionsFound, $keywordCount, $resumeAgeDays, $hasPortfolioLinks);
        $warnings = $this->buildWarnings($metadata, $completionPercentage, $profile, $resumeAgeDays);
        $suggestions = $this->buildSuggestions($metadata, $completionPercentage, $profile, $hasPortfolioLinks, $resumeAgeDays);
        $score = $this->scoreResume($metadata, $completionPercentage, $profile, $hasPortfolioLinks, $resumeAgeDays);
        $readiness = $this->calculateReadiness($score);

        return new ResumeAnalysisResult(
            $score,
            array_values(array_unique($strengths)),
            array_values(array_unique($warnings)),
            array_values(array_unique($suggestions)),
            $missingSections,
            $readiness,
            now()->toDateTimeString(),
            $keywordCount,
            $estimatedATS,
            $atsScore,
            $resumeAgeDays
        );
    }

    protected function calculateATSScore(bool $analysisAvailable, array $metadata, array $sectionsFound, int $keywordCount, int $completionPercentage): int
    {
        if (! $analysisAvailable) {
            return 0;
        }

        $score = 0;
        $score += 20;
        $score += $metadata['validSize'] ? 10 : 0;
        $score += min(30, count($sectionsFound) * 6);
        $score += min(30, $keywordCount * 3);
        $score += min(10, (int) round($completionPercentage / 10));

        if (count($sectionsFound) >= 4 && $keywordCount >= 8) {
            $score += 10;
        }

        if ($keywordCount === 0) {
            $score -= 5;
        }

        if (count($sectionsFound) < 3) {
            $score -= 5;
        }

        return max(0, min(100, $score));
    }

    protected function extractMetadata(Resume $resume): array
    {
        $fileType = strtolower((string) $resume->file_type);
        $extension = Str::afterLast($fileType, '.');
        $normalizedExtension = $this->normalizeExtension($extension ?: $fileType);

        return [
            'uploaded' => ! empty($resume->file_path),
            'title' => ! empty($resume->title),
            'default' => (bool) $resume->is_default,
            'active' => $resume->is_active,
            'validType' => in_array($normalizedExtension, self::VALID_EXTENSIONS, true),
            'validSize' => is_int($resume->file_size) && $resume->file_size > 0 && $resume->file_size <= self::MAX_FILE_SIZE,
        ];
    }

    protected function extractText(Resume $resume): string
    {
        if (empty($resume->file_path) || ! Storage::disk('public')->exists($resume->file_path)) {
            return '';
        }

        $path = Storage::disk('public')->path($resume->file_path);
        $extension = $this->normalizeExtension(strtolower(pathinfo($path, PATHINFO_EXTENSION)));

        return match ($extension) {
            'pdf' => $this->extractTextFromPdf($path),
            'doc', 'docx' => $this->extractTextFromDocx($path),
            default => '',
        };
    }

    protected function extractTextFromPdf(string $path): string
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $document = $parser->parseFile($path);

            return (string) $document->getText();
        } catch (\Throwable) {
            return '';
        }
    }

    protected function extractTextFromDocx(string $path): string
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                $text .= ' ' . $this->extractTextFromElements($section->getElements());
            }

            return trim($text);
        } catch (\Throwable) {
            return '';
        }
    }

    protected function extractTextFromElements(array $elements): string
    {
        $text = '';

        foreach ($elements as $element) {
            if (method_exists($element, 'getText') && ! method_exists($element, 'getElements')) {
                $text .= ' ' . $element->getText();
                continue;
            }

            if (method_exists($element, 'getElements')) {
                $text .= ' ' . $this->extractTextFromElements($element->getElements());
                continue;
            }

            if (method_exists($element, 'getRows')) {
                foreach ($element->getRows() as $row) {
                    $text .= ' ' . $this->extractTextFromElements($row->getCells());
                }
            }

            if (method_exists($element, 'getCells')) {
                $text .= ' ' . $this->extractTextFromElements($element->getCells());
            }
        }

        return trim($text);
    }

    protected function detectSections(string $text): array
    {
        $normalized = strtolower($text);
        $found = [];

        $patterns = [
            'summary' => '/\b(professional summary|summary|career objective|objective)\b/i',
            'experience' => '/\b(experience|employment history|work history|professional experience)\b/i',
            'education' => '/\b(education|academic background|qualifications)\b/i',
            'skills' => '/\b(skills|technical skills|core competencies|expertise)\b/i',
            'contact' => '/\b(contact|email|phone|address|linkedin|website)\b/i',
        ];

        foreach ($patterns as $section => $pattern) {
            if (preg_match($pattern, $normalized)) {
                $found[] = $section;
            }
        }

        return array_values(array_unique($found));
    }

    protected function countKeywords(string $text): int
    {
        $normalized = strtolower($text);

        return collect($this->keywordDictionary)
            ->map(fn (string $keyword) => preg_match_all('/\b' . preg_quote($keyword, '/') . '\b/i', $normalized))
            ->sum();
    }

    protected function estimateATSReadiness(int $keywordCount, array $sectionsFound, bool $validType): string
    {
        if (! $validType) {
            return 'Unsupported file type';
        }

        if ($keywordCount >= 12 && count($sectionsFound) >= 4) {
            return 'High';
        }

        if ($keywordCount >= 6 && count($sectionsFound) >= 3) {
            return 'Moderate';
        }

        return 'Low';
    }

    protected function buildStrengths(array $metadata, int $completionPercentage, array $sectionsFound, int $keywordCount, ?int $resumeAgeDays, bool $hasPortfolioLinks): array
    {
        $strengths = [];

        if ($metadata['uploaded']) {
            $strengths[] = 'Resume uploaded';
        }
        if ($metadata['title']) {
            $strengths[] = 'Resume title is set';
        }
        if ($metadata['default']) {
            $strengths[] = 'Default resume selected';
        }
        if ($metadata['validType']) {
            $strengths[] = 'Valid resume file type';
        }
        if ($metadata['validSize']) {
            $strengths[] = 'Acceptable resume file size';
        }
        if ($metadata['active']) {
            $strengths[] = 'Resume is active';
        }
        if ($completionPercentage >= 80) {
            $strengths[] = 'Strong profile completion';
            $strengths[] = 'Good profile completion';
        }
        if ($hasPortfolioLinks) {
            $strengths[] = 'Portfolio or professional links provided';
        }
        if ($resumeAgeDays !== null && $resumeAgeDays <= self::RECENT_DAYS) {
            $strengths[] = 'Resume updated recently';
        }

        foreach ($sectionsFound as $section) {
            $strengths[] = 'Contains ' . ucfirst($section) . ' section';
        }

        if ($keywordCount > 0) {
            $strengths[] = 'Includes ' . $keywordCount . ' ATS keyword' . ($keywordCount > 1 ? 's' : '');
        }

        return $strengths;
    }

    protected function buildWarnings(array $metadata, int $completionPercentage, ?JobSeekerProfile $profile, ?int $resumeAgeDays): array
    {
        $warnings = [];

        if (! $metadata['uploaded']) {
            $warnings[] = 'Resume file is missing.';
        }
        if ($metadata['uploaded'] && ! $metadata['validType']) {
            $warnings[] = 'Unsupported resume file type.';
        }
        if ($metadata['uploaded'] && ! $metadata['validSize']) {
            $warnings[] = 'Resume file is larger than 5 MB.';
        }
        if (! $metadata['active']) {
            $warnings[] = 'This resume is inactive.';
        }
        if ($completionPercentage < 50) {
            $warnings[] = 'Profile completion is below 50%.';
        }
        if ($resumeAgeDays !== null && $resumeAgeDays > self::OLD_DAYS) {
            $warnings[] = 'Resume has not been updated in over 6 months.';
        }
        if ($profile && ! $profile->educations()->exists()) {
            $warnings[] = 'No education records are present.';
        }
        if ($profile && ! $profile->experiences()->exists()) {
            $warnings[] = 'No work experience is recorded.';
        }
        if ($profile && ! $profile->skills()->exists()) {
            $warnings[] = 'No skills are listed.';
        }

        return $warnings;
    }

    protected function buildSuggestions(array $metadata, int $completionPercentage, ?JobSeekerProfile $profile, bool $hasPortfolioLinks, ?int $resumeAgeDays): array
    {
        $suggestions = [];

        if (! $metadata['uploaded']) {
            $suggestions[] = 'Upload your resume file.';
        }
        if (! $metadata['title']) {
            $suggestions[] = 'Use a better resume title.';
        }
        if ($metadata['uploaded'] && ! $metadata['validType']) {
            $suggestions[] = 'Upload PDF or DOCX instead of unsupported file types.';
        }
        if ($metadata['uploaded'] && ! $metadata['validSize']) {
            $suggestions[] = 'Reduce the resume file size to under 5 MB.';
        }
        if (! $metadata['default']) {
            $suggestions[] = 'Mark this resume as the default resume.';
        }
        if (! $metadata['active']) {
            $suggestions[] = 'Activate this resume for employer visibility.';
        }
        if ($completionPercentage < 80) {
            $suggestions[] = 'Improve profile completion to boost resume strength.';
        }
        if (! $hasPortfolioLinks) {
            $suggestions[] = 'Add portfolio or LinkedIn links.';
        }
        if ($resumeAgeDays !== null && $resumeAgeDays > self::OLD_DAYS) {
            $suggestions[] = 'Update your resume if it is older than 6 months.';
        }
        if ($profile && ! $profile->educations()->exists()) {
            $suggestions[] = 'Add more education to your profile.';
        }
        if ($profile && ! $profile->experiences()->exists()) {
            $suggestions[] = 'Add work experience entries.';
        }
        if ($profile && ! $profile->skills()->exists()) {
            $suggestions[] = 'Add more skills to your profile.';
        }

        return $suggestions;
    }

    protected function scoreResume(array $metadata, int $completionPercentage, ?JobSeekerProfile $profile, bool $hasPortfolioLinks, ?int $resumeAgeDays): int
    {
        $score = 0;

        $score += $metadata['uploaded'] ? self::SCORE_WEIGHTS['uploaded'] : 0;
        $score += $metadata['title'] ? self::SCORE_WEIGHTS['title'] : 0;
        $score += $metadata['default'] ? self::SCORE_WEIGHTS['default'] : 0;
        $score += $metadata['validType'] ? self::SCORE_WEIGHTS['valid_type'] : 0;
        $score += $metadata['validSize'] ? self::SCORE_WEIGHTS['valid_size'] : 0;
        $score += $metadata['active'] ? self::SCORE_WEIGHTS['active'] : 0;
        $score += $hasPortfolioLinks ? self::SCORE_WEIGHTS['portfolio'] : 0;
        $score += $resumeAgeDays !== null && $resumeAgeDays <= self::RECENT_DAYS ? self::SCORE_WEIGHTS['recent_upload'] : 0;
        $score += (int) round(min($completionPercentage, 100) / 100 * self::SCORE_WEIGHTS['profile_completion']);
        $score += $profile && $profile->educations()->exists() ? self::SCORE_WEIGHTS['education'] : 0;
        $score += $profile && $profile->experiences()->exists() ? self::SCORE_WEIGHTS['experience'] : 0;
        $score += $profile && $profile->skills()->exists() ? self::SCORE_WEIGHTS['skills'] : 0;

        return max(0, min(100, $score));
    }

    protected function calculateReadiness(int $score): string
    {
        return match (true) {
            $score >= 85 => 'Excellent',
            $score >= 70 => 'Good',
            $score >= 50 => 'Needs Improvement',
            default => 'Incomplete',
        };
    }

    protected function hasPortfolioLinks(?JobSeekerProfile $profile): bool
    {
        if (! $profile) {
            return false;
        }

        return collect([
            $profile->linkedin_url,
            $profile->github_url,
            $profile->portfolio_url,
            $profile->website_url,
        ])->filter(fn ($value) => ! empty($value))->isNotEmpty();
    }

    protected function normalizeExtension(string $value): string
    {
        $value = strtolower(trim($value));

        return match ($value) {
            'application/msword', 'doc' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'docx' => 'docx',
            'application/pdf', 'pdf' => 'pdf',
            default => $value,
        };
    }
}
