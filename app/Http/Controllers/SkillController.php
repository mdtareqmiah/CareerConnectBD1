<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();
        $skills = $profile ? $profile->skills()->orderBy('skill_name')->get() : collect();

        return view('job-seeker.skills.index', compact('skills'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add skills.');
        }

        return view('job-seeker.skills.create');
    }

    public function store(StoreSkillRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add skills.');
        }

        $profile->skills()->create($request->validated());

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.skills.index')->with('success', 'Skill created successfully.');
    }

    public function edit(Request $request, Skill $skill): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($skill->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        return view('job-seeker.skills.edit', compact('skill'));
    }

    public function update(UpdateSkillRequest $request, Skill $skill): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($skill->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $skill->update($request->validated());

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($skill->jobSeekerProfile);

        return redirect()->route('job-seeker.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Request $request, Skill $skill): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($skill->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $profile = $skill->jobSeekerProfile;
        $skill->delete();

        app(\App\Services\ResumeAnalysisService::class)->invalidateByProfile($profile);

        return redirect()->route('job-seeker.skills.index')->with('success', 'Skill deleted successfully.');
    }
}
