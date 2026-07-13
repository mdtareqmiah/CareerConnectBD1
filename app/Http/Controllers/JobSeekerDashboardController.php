<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JobRecommendationService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobSeekerDashboardController extends Controller
{
    public function __construct(
        private readonly ProfileCompletionService $profileCompletionService,
        private readonly JobRecommendationService $jobRecommendationService
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()
            ->with(['educations', 'experiences', 'skills', 'resumes'])
            ->first();

        $educations = $profile?->educations ?? collect();
        $experiences = $profile?->experiences ?? collect();
        $skills = $profile?->skills ?? collect();
        $resumes = $profile?->resumes ?? collect();

        $completionDetails = $profile
            ? $this->profileCompletionService->getCompletionDetails($profile)
            : [
                'percentage' => 0,
                'completed_sections' => [],
                'missing_sections' => ['basic_profile', 'professional_summary', 'education', 'experience', 'skills', 'resume', 'profile_photo'],
            ];

        $educationCount = $educations->count();
        $experienceCount = $experiences->count();
        $skillsCount = $skills->count();
        $resumeCount = $resumes->count();
        $defaultResume = $resumes->firstWhere('is_default', true);
        $profileStatus = $completionDetails['percentage'] >= 100 ? 'Complete' : 'Incomplete';
        $profileStatusClass = $completionDetails['percentage'] >= 100 ? 'success' : 'warning';
        $completionBadgeClass = $completionDetails['percentage'] >= 75 ? 'success' : ($completionDetails['percentage'] >= 40 ? 'warning' : 'secondary');

        $recommendedJobs = $profile
            ? $this->jobRecommendationService->recommendForProfile($profile)
            : collect();

        $recentActivities = collect()
            ->merge($educations->map(fn ($education) => [
                'type' => 'Education',
                'title' => $education->degree ?? 'Education',
                'description' => $education->institution_name ?? 'Education entry',
                'created_at' => $education->created_at,
            ]))
            ->merge($experiences->map(fn ($experience) => [
                'type' => 'Experience',
                'title' => $experience->job_title ?? 'Experience',
                'description' => $experience->company_name ?? 'Experience entry',
                'created_at' => $experience->created_at,
            ]))
            ->merge($skills->map(fn ($skill) => [
                'type' => 'Skill',
                'title' => $skill->skill_name ?? 'Skill',
                'description' => $skill->proficiency_level ?? 'Skill entry',
                'created_at' => $skill->created_at,
            ]))
            ->merge($resumes->map(fn ($resume) => [
                'type' => 'Resume',
                'title' => $resume->title ?? 'Resume',
                'description' => $resume->is_default ? 'Default resume' : 'Resume uploaded',
                'created_at' => $resume->created_at,
            ]))
            ->sortByDesc('created_at')
            ->take(6)
            ->values();

        return view('job-seeker.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'completionDetails' => $completionDetails,
            'educationCount' => $educationCount,
            'experienceCount' => $experienceCount,
            'skillsCount' => $skillsCount,
            'resumeCount' => $resumeCount,
            'defaultResume' => $defaultResume,
            'profileStatus' => $profileStatus,
            'profileStatusClass' => $profileStatusClass,
            'completionBadgeClass' => $completionBadgeClass,
            'availabilityStatus' => $profile?->is_available_for_work ? 'Available for Work' : 'Not Available',
            'recentActivities' => $recentActivities,
            'educations' => $educations,
            'experiences' => $experiences,
            'skills' => $skills,
            'resumes' => $resumes,
            'recommendedJobs' => $recommendedJobs,
        ]);
    }
}
