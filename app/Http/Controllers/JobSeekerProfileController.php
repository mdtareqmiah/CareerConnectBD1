<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobSeekerProfileRequest;
use App\Http\Requests\UpdateJobSeekerProfileRequest;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobSeekerProfileController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($user->jobSeekerProfile()->exists()) {
            return redirect()->route('job-seeker.profile.edit');
        }

        return view('job-seeker.profile.create');
    }

    public function edit(?JobSeekerProfile $profile = null): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $profile ?? $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to continue.');
        }

        if ($profile->user_id !== $user->id) {
            abort(403);
        }

        return view('job-seeker.profile.edit', compact('profile'));
    }

    public function store(StoreJobSeekerProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $this->storeProfilePhoto($request->file('profile_photo'));
        }

        if ($user->jobSeekerProfile()->exists()) {
            return redirect()->route('job-seeker.profile.edit');
        }

        JobSeekerProfile::create(array_merge($data, [
            'user_id' => $user->id,
            'is_profile_completed' => true,
            'is_available_for_work' => $request->boolean('is_available_for_work'),
        ]));

        return redirect()->route('job-seeker.dashboard')->with('success', 'Profile created successfully.');
    }

    public function update(UpdateJobSeekerProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to continue.');
        }

        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $this->storeProfilePhoto($request->file('profile_photo'), $profile->profile_photo);
        }

        $profile->update(array_merge($data, [
            'is_profile_completed' => true,
            'is_available_for_work' => $request->boolean('is_available_for_work'),
        ]));

        return redirect()->route('job-seeker.dashboard')->with('success', 'Profile updated successfully.');
    }

    private function storeProfilePhoto($photo, ?string $previousPath = null): string
    {
        if ($previousPath && Storage::disk('public')->exists($previousPath)) {
            Storage::disk('public')->delete($previousPath);
        }

        $filename = Str::uuid()->toString() . '.' . $photo->getClientOriginalExtension();

        return $photo->storeAs('profile-photos', $filename, 'public');
    }
}
