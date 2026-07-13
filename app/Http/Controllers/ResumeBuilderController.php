<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResumeBuilderRequest;
use App\Http\Requests\UpdateResumeBuilderRequest;
use App\Models\JobSeekerProfile;
use App\Models\ResumeBuilder;
use App\Services\ResumePdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResumeBuilderController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile, 403);

        $resumeBuilders = $profile->resumeBuilders()->orderByDesc('is_default')->orderByDesc('updated_at')->get();

        return view('job-seeker.resume-builders.index', compact('resumeBuilders'));
    }

    public function create(Request $request): View
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile, 403);

        $duplicateBuilder = null;
        if ($request->query('duplicate')) {
            $duplicateBuilder = $profile->resumeBuilders()->find($request->query('duplicate'));
            abort_unless($duplicateBuilder, 404);
        }

        return view('job-seeker.resume-builders.create', compact('duplicateBuilder'));
    }

    public function store(StoreResumeBuilderRequest $request)
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile, 403);

        $validated = $request->validated();
        $validated['job_seeker_profile_id'] = $profile->id;
        $validated['is_default'] = (bool) ($validated['is_default'] ?? false);

        if ($validated['is_default']) {
            $profile->resumeBuilders()->update(['is_default' => false]);
        }

        $resumeBuilder = ResumeBuilder::create($validated);

        if ($request->wantsJson() || $request->isJson()) {
            return response()->json(['id' => $resumeBuilder->id], 201);
        }

        return redirect()->route('job-seeker.resume-builders.index')->with('success', 'Resume builder created successfully.');
    }

    public function edit(Request $request, ResumeBuilder $resumeBuilder): View
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        return view('job-seeker.resume-builders.edit', compact('resumeBuilder'));
    }

    public function update(UpdateResumeBuilderRequest $request, ResumeBuilder $resumeBuilder)
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        $validated = $request->validated();
        $validated['is_default'] = (bool) ($validated['is_default'] ?? false);

        if ($validated['is_default']) {
            $profile->resumeBuilders()->where('id', '!=', $resumeBuilder->id)->update(['is_default' => false]);
        }

        $resumeBuilder->update($validated);

        if ($request->wantsJson() || $request->isJson()) {
            return response()->json(['id' => $resumeBuilder->id]);
        }

        return redirect()->route('job-seeker.resume-builders.index')->with('success', 'Resume builder updated successfully.');
    }

    public function destroy(Request $request, ResumeBuilder $resumeBuilder): RedirectResponse
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        $resumeBuilder->delete();

        return redirect()->route('job-seeker.resume-builders.index')->with('success', 'Resume builder deleted successfully.');
    }

    public function preview(Request $request, ResumeBuilder $resumeBuilder): View
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        $template = $request->query('template', $resumeBuilder->template ?: 'modern');
        if (! in_array($template, ResumePdfService::TEMPLATES, true)) {
            abort(404);
        }

        return view('job-seeker.resume-builder.preview', [
            'builder' => $resumeBuilder,
            'template' => $template,
            'templates' => ResumePdfService::TEMPLATES,
        ]);
    }

    public function downloadPdf(Request $request, ResumeBuilder $resumeBuilder, ResumePdfService $pdfService)
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        $template = $request->query('template', $resumeBuilder->template ?: 'modern');

        return $pdfService->download($resumeBuilder, $template);
    }

    public function print(Request $request, ResumeBuilder $resumeBuilder, ResumePdfService $pdfService)
    {
        $profile = $request->user()?->jobSeekerProfile;

        abort_unless($profile && $resumeBuilder->job_seeker_profile_id === $profile->id, 403);

        $template = $request->query('template', $resumeBuilder->template ?: 'modern');

        $html = $pdfService->renderHtml($resumeBuilder, $template);

        return response($html)->header('Content-Type', 'text/html');
    }
}
