<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobSeekerDashboardController extends Controller
{
    public function __construct(private readonly ProfileCompletionService $profileCompletionService)
    {
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

        $completionDetails = $profile
            ? $this->profileCompletionService->getCompletionDetails($profile)
            : [
                'percentage' => 0,
                'completed_sections' => [],
                'missing_sections' => ['basic_profile', 'professional_summary', 'education', 'experience', 'skills', 'resume', 'profile_photo'],
            ];

        $educationCount = $profile?->educations->count() ?? 0;
        $experienceCount = $profile?->experiences->count() ?? 0;
        $skillsCount = $profile?->skills->count() ?? 0;
        $resumeCount = $profile?->resumes->count() ?? 0;

        return view('job-seeker.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'completionDetails' => $completionDetails,
            'educationCount' => $educationCount,
            'experienceCount' => $experienceCount,
            'skillsCount' => $skillsCount,
            'resumeCount' => $resumeCount,
            'profileStatus' => $profile ? 'Complete' : 'Not Created',
            'availabilityStatus' => $profile?->is_available_for_work ? 'Available for Work' : 'Not Available',
        ]);
    }
}
