<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();
        $resumes = $profile ? $profile->resumes()->orderByDesc('is_default')->orderByDesc('created_at')->get() : collect();

        return view('job-seeker.resumes.index', compact('resumes'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add resumes.');
        }

        return view('job-seeker.resumes.create');
    }

    public function store(StoreResumeRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $profile = $user->jobSeekerProfile()->first();

        if (! $profile) {
            return redirect()->route('job-seeker.profile.create')->with('info', 'Create your profile first to add resumes.');
        }

        $validated = $request->validated();
        $file = $request->file('file_path');

        $path = $file->store('resumes', 'public');

        $resume = $profile->resumes()->create([
            'title' => $validated['title'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'is_default' => (bool) ($validated['is_default'] ?? false),
            'uploaded_at' => now(),
        ]);

        if ($resume->is_default) {
            $profile->resumes()->where('id', '!=', $resume->id)->update(['is_default' => false]);
        }

        return redirect()->route('job-seeker.resumes.index')->with('success', 'Resume uploaded successfully.');
    }

    public function edit(Request $request, Resume $resume): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($resume->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        return view('job-seeker.resumes.edit', compact('resume'));
    }

    public function update(UpdateResumeRequest $request, Resume $resume): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($resume->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($resume->file_path && Storage::disk('public')->exists($resume->file_path)) {
                Storage::disk('public')->delete($resume->file_path);
            }

            $file = $request->file('file_path');
            $path = $file->store('resumes', 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
            $validated['uploaded_at'] = now();
        }

        $resume->update($validated);

        if ($resume->is_default) {
            $resume->jobSeekerProfile->resumes()->where('id', '!=', $resume->id)->update(['is_default' => false]);
        }

        return redirect()->route('job-seeker.resumes.index')->with('success', 'Resume updated successfully.');
    }

    public function destroy(Request $request, Resume $resume): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($resume->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        if ($resume->file_path && Storage::disk('public')->exists($resume->file_path)) {
            Storage::disk('public')->delete($resume->file_path);
        }

        $resume->delete();

        return redirect()->route('job-seeker.resumes.index')->with('success', 'Resume deleted successfully.');
    }

    public function download(Request $request, Resume $resume)
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($resume->jobSeekerProfile->user_id !== $user->id) {
            abort(403);
        }

        if (! $resume->file_path || ! Storage::disk('public')->exists($resume->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($resume->file_path, $resume->file_name ?: basename($resume->file_path));
    }
}
