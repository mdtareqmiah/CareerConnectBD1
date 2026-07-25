<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmployerJobApplicationStatusRequest;
use App\Services\EmployerJobService;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EmployerApplicationController extends Controller
{
    private EmployerJobService $jobService;

    public function __construct(
        EmployerJobService $jobService,
        private readonly NotificationService $notificationService,
    )
    {
        $this->jobService = $jobService;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', JobApplication::class);

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,reviewed,shortlisted,rejected,accepted'],
            'job_id' => ['nullable', 'integer', 'exists:job_listings,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'string', 'in:newest,oldest'],
        ]);

        $applications = $this->jobService->listApplications($request->user(), $validated);
        $jobs = $this->jobService->applicationJobs($request->user());

        return view('employer.applications.index', compact('applications', 'jobs'));
    }

    public function show(JobApplication $jobApplication): View
    {
        $this->authorize('view', $jobApplication);

        $jobApplication->load([
            'job.company',
            'user.jobSeekerProfile.educations',
            'user.jobSeekerProfile.experiences',
            'user.jobSeekerProfile.skills',
            'resume',
        ]);

        $profile = $jobApplication->user->jobSeekerProfile;
        $profileCompletion = $this->calculateProfileCompletion($profile);
        $timeline = $this->buildApplicationTimeline($jobApplication);
        $matchService = app(\App\Services\CandidateMatchService::class);
        $matchScore = $matchService->calculate(
            $jobApplication->job,
            $profile ?? new \App\Models\JobSeekerProfile()
        );

        $resumeAnalysis = null;
        if ($jobApplication->resume) {
            $analysis = app(\App\Services\ResumeAnalysisService::class)->analyze($jobApplication->resume);
            $resumeAnalysis = is_object($analysis) && method_exists($analysis, 'toArray') ? $analysis->toArray() : (array) $analysis;
        }

        $matchedSkills = $profile ? $matchService->matchedSkills($jobApplication->job, $profile) : collect();
        $missingSkills = $profile ? $matchService->missingSkills($jobApplication->job, $profile) : collect();

        return view('employer.applications.show', compact('jobApplication', 'profileCompletion', 'timeline', 'matchScore', 'resumeAnalysis', 'matchedSkills', 'missingSkills'));
    }

    public function previewResume(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        $resume = $jobApplication->resume;

        if (! $resume || ! $resume->file_path || ! Storage::disk('public')->exists($resume->file_path)) {
            abort(404);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->response($resume->file_path, $resume->file_name ?: basename($resume->file_path));
    }

    public function updateStatus(UpdateEmployerJobApplicationStatusRequest $request, JobApplication $jobApplication): RedirectResponse
    {
        $this->authorize('updateStatus', $jobApplication);

        $newStatus = $request->validated()['status'];
        $previousStatus = $jobApplication->status;

        if ($previousStatus !== $newStatus) {
            $jobApplication->update(['status' => $newStatus]);

            $jobApplication->loadMissing(['job.company', 'user']);

            $this->notificationService->notifyApplicationStatusChanged($jobApplication->user, [
                'title' => 'Application status updated',
                'message' => 'Your application for '.$jobApplication->job->title.' has been updated to '.$jobApplication->status_label.'.',
                'link' => route('job-seeker.applications.show', $jobApplication),
                'application_id' => $jobApplication->id,
                'job_id' => $jobApplication->job_id,
                'job_title' => $jobApplication->job->title,
                'status' => $jobApplication->status,
                'status_label' => $jobApplication->status_label,
            ]);
        }

        return redirect()->route('employer.applications.show', $jobApplication)->with('success', 'Application status updated successfully.');
    }

    public function downloadResume(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        $resume = $jobApplication->resume;

        if (! $resume || ! $resume->file_path || ! Storage::disk('public')->exists($resume->file_path)) {
            abort(404);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($resume->file_path, $resume->file_name ?: basename($resume->file_path));
    }

    private function calculateProfileCompletion(?\App\Models\JobSeekerProfile $profile): int
    {
        if (! $profile) {
            return 0;
        }

        $fields = [
            $profile->first_name,
            $profile->last_name,
            $profile->phone,
            $profile->address,
            $profile->gender,
            $profile->date_of_birth,
            $profile->professional_title,
            $profile->professional_summary,
        ];

        $filled = collect($fields)->filter(fn ($value) => ! empty($value))->count();

        return (int) round($filled / count($fields) * 100);
    }

    private function buildApplicationTimeline(JobApplication $jobApplication): array
    {
        return [
            [
                'label' => 'Application Submitted',
                'time' => $jobApplication->applied_at?->format('M d, Y H:i') ?? $jobApplication->created_at->format('M d, Y H:i'),
                'active' => true,
            ],
            [
                'label' => $jobApplication->status_label,
                'time' => $jobApplication->updated_at->format('M d, Y H:i'),
                'active' => true,
            ],
        ];
    }
}
