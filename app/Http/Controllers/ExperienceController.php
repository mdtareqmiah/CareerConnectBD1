<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();
        $experiences = $profile ? $profile->experiences()->orderByDesc('start_date')->get() : collect();

        return view('job-seeker.experiences.index', compact('experiences'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add experience.');
        }

        return view('job-seeker.experiences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add experience.');
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'currently_working' => ['nullable', 'boolean'],
            'job_description' => ['nullable', 'string'],
        ]);

        $profile->experiences()->create($validated);

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.experiences.index')->with('success', 'Experience created successfully.');
    }

    public function edit(Request $request, Experience $experience): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($experience->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        return view('job-seeker.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($experience->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'currently_working' => ['nullable', 'boolean'],
            'job_description' => ['nullable', 'string'],
        ]);

        $experience->update($validated);

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($experience->jobSeekerProfile);

        return redirect()->route('job-seeker.experiences.index')->with('success', 'Experience updated successfully.');
    }

    public function destroy(Request $request, Experience $experience): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($experience->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $profile = $experience->jobSeekerProfile;
        $experience->delete();

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.experiences.index')->with('success', 'Experience deleted successfully.');
    }
}
