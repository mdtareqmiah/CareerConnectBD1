<?php

namespace App\Services;

use App\Models\JobSeekerProfile;

class ProfileCompletionService
{
    /**
     * Section weights used to calculate overall completion.
     */
    private const SECTION_WEIGHTS = [
        'basic_profile' => 20,
        'professional_summary' => 15,
        'education' => 15,
        'experience' => 15,
        'skills' => 10,
        'resume' => 10,
        'profile_photo' => 15,
    ];

    /**
     * Get completion details for a job seeker profile.
     *
     * @return array{percentage:int, completed_sections:array<int,string>, missing_sections:array<int,string>}
     */
    public function getCompletionDetails(JobSeekerProfile $profile): array
    {
        $completedSections = [];
        $missingSections = [];

        if ($this->hasBasicProfile($profile)) {
            $completedSections[] = 'basic_profile';
        } else {
            $missingSections[] = 'basic_profile';
        }

        if ($this->hasProfessionalSummary($profile)) {
            $completedSections[] = 'professional_summary';
        } else {
            $missingSections[] = 'professional_summary';
        }

        if ($this->hasEducation($profile)) {
            $completedSections[] = 'education';
        } else {
            $missingSections[] = 'education';
        }

        if ($this->hasExperience($profile)) {
            $completedSections[] = 'experience';
        } else {
            $missingSections[] = 'experience';
        }

        if ($this->hasSkills($profile)) {
            $completedSections[] = 'skills';
        } else {
            $missingSections[] = 'skills';
        }

        if ($this->hasResume($profile)) {
            $completedSections[] = 'resume';
        } else {
            $missingSections[] = 'resume';
        }

        if ($this->hasProfilePhoto($profile)) {
            $completedSections[] = 'profile_photo';
        } else {
            $missingSections[] = 'profile_photo';
        }

        $percentage = $this->calculatePercentage($completedSections);

        return [
            'percentage' => $percentage,
            'completed_sections' => $completedSections,
            'missing_sections' => $missingSections,
        ];
    }

    /**
     * Get the completion percentage for a profile.
     */
    public function getCompletionPercentage(JobSeekerProfile $profile): int
    {
        return $this->getCompletionDetails($profile)['percentage'];
    }

    private function calculatePercentage(array $completedSections): int
    {
        $totalWeight = array_sum(self::SECTION_WEIGHTS);
        $completedWeight = 0;

        foreach ($completedSections as $section) {
            if (isset(self::SECTION_WEIGHTS[$section])) {
                $completedWeight += self::SECTION_WEIGHTS[$section];
            }
        }

        return (int) round(($completedWeight / $totalWeight) * 100);
    }

    private function hasBasicProfile(JobSeekerProfile $profile): bool
    {
        return ! empty($profile->first_name)
            && ! empty($profile->last_name)
            && ! empty($profile->phone)
            && ! empty($profile->date_of_birth)
            && ! empty($profile->gender)
            && ! empty($profile->city)
            && ! empty($profile->country);
    }

    private function hasProfessionalSummary(JobSeekerProfile $profile): bool
    {
        return ! empty($profile->professional_summary);
    }

    private function hasEducation(JobSeekerProfile $profile): bool
    {
        return $profile->educations()->exists();
    }

    private function hasExperience(JobSeekerProfile $profile): bool
    {
        return $profile->experiences()->exists();
    }

    private function hasSkills(JobSeekerProfile $profile): bool
    {
        return $profile->skills()->exists();
    }

    private function hasResume(JobSeekerProfile $profile): bool
    {
        return $profile->resumes()->exists();
    }

    private function hasProfilePhoto(JobSeekerProfile $profile): bool
    {
        return ! empty($profile->profile_photo);
    }
}
