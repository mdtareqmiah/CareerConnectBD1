<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    public function index(Request $request): View
    {
        $savedJobs = SavedJob::query()
            ->with('job.company')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('job-seeker.saved_jobs.index', compact('savedJobs'));
    }

    public function toggle(Job $job): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login')->with('status', 'Please login to save jobs.');
        }

        if ($user->role?->slug !== 'job-seeker') {
            abort(403);
        }

        $saved = SavedJob::firstOrCreate([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        if (! $saved->wasRecentlyCreated) {
            $saved->delete();
            return back()->with('success', 'Job removed from saved jobs.');
        }

        return back()->with('success', 'Job saved successfully.');
    }
}
