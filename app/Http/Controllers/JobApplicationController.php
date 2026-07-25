<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    public function index()
    {
        //
    }

    public function redirectToLogin(Job $job)
    {
        if (Auth::check() && Auth::user()->role?->slug !== 'job-seeker') {
            abort(403);
        }

        if (Auth::check()) {
            return redirect()->route('job-applications.create', ['job_id' => $job->id]);
        }

        session()->put('url.intended', route('jobs.show', $job));
        session()->flash('status', 'Please complete your application.');

        return redirect()->route('login');
    }

    public function create(Request $request)
    {
        $job = Job::findOrFail($request->query('job_id'));

        if (! $job->isOpen()) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This job is no longer accepting applications.');
        }

        $user = $request->user();

        if ($job->alreadyAppliedBy($user)) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        $resumes = $user->jobSeekerProfile?->resumes()->where('is_active', true)->get() ?? collect();

        return view('job_applications.create', compact('job', 'resumes'));
    }

    public function store(StoreJobApplicationRequest $request)
    {
        $job = Job::findOrFail($request->input('job_id'));
        $user = $request->user();

        if (! $job->isOpen()) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This job is no longer accepting applications.');
        }

        if ($job->alreadyAppliedBy($user)) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $request->input('resume_id'),
            'cover_letter' => $request->input('cover_letter'),
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        if ($application && $job->company?->employer) {
            $this->notificationService->notifyJobApplied($job->company->employer, [
                'title' => 'New application received',
                'message' => 'New application received for '.$job->title.'.',
                'link' => route('employer.applications.show', $application),
                'application_id' => $application->id,
                'job_id' => $job->id,
                'job_title' => $job->title,
                'applicant_id' => $user->id,
                'applicant_name' => $user->name,
            ]);
        }

        return redirect()->route('jobs.show', $job)
            ->with('status', 'Application submitted successfully.');
    }

    public function show(JobApplication $jobApplication)
    {
        //
    }

    public function edit(JobApplication $jobApplication)
    {
        //
    }

    public function update(Request $request, JobApplication $jobApplication)
    {
        //
    }

    public function destroy(JobApplication $jobApplication)
    {
        //
    }
}
