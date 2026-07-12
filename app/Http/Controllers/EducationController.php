<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEducationRequest;
use App\Http\Requests\UpdateEducationRequest;
use App\Models\Education;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();
        $educations = $profile ? $profile->educations()->orderByDesc('passing_year')->get() : collect();

        return view('job-seeker.educations.index', compact('educations'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add education.');
        }

        return view('job-seeker.educations.create');
    }

    public function store(StoreEducationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add education.');
        }

        $profile->educations()->create($request->validated());

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.educations.index')->with('success', 'Education created successfully.');
    }

    public function edit(Request $request, Education $education): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($education->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        return view('job-seeker.educations.edit', compact('education'));
    }

    public function update(UpdateEducationRequest $request, Education $education): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($education->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $education->update($request->validated());

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($education->jobSeekerProfile);

        return redirect()->route('job-seeker.educations.index')->with('success', 'Education updated successfully.');
    }

    public function destroy(Request $request, Education $education): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($education->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $profile = $education->jobSeekerProfile;
        $education->delete();

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.educations.index')->with('success', 'Education deleted successfully.');
    }
}
