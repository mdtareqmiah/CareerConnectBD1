<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\EmployerJobTrashRequest;
use App\Models\Company;
use App\Models\Job;
use App\Services\EmployerJobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobController extends Controller
{
    private EmployerJobService $jobService;

    public function __construct(EmployerJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index(Request $request): View
    {
        if ($request->user()?->role?->slug === 'employer') {
            $validated = $request->validate([
                'search' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', Rule::in(['draft', 'published', 'closed', 'expired'])],
                'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
            ]);

            $jobs = $this->jobService->listJobs($request->user(), $validated);
            $stats = $this->jobService->stats($request->user());

            return view('jobs.index', compact('jobs', 'stats'));
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
        ]);

        $jobTypes = Job::query()
            ->where('status', 'published')
            ->whereDate('deadline', '>=', today())
            ->distinct()
            ->orderBy('job_type')
            ->pluck('job_type');

        $jobs = Job::with('company')
            ->where('status', 'published')
            ->whereDate('deadline', '>=', today())
            ->search($validated['search'] ?? null)
            ->jobTypeFilter($validated['job_type'] ?? null)
            ->salaryRange(
                isset($validated['salary_min']) ? (int) $validated['salary_min'] : null,
                isset($validated['salary_max']) ? (int) $validated['salary_max'] : null,
            )
            ->sortBy($validated['sort'] ?? 'newest')
            ->paginate(10)
            ->appends($request->query());

        return view('jobs.public_index', compact('jobs', 'jobTypes'));
    }

    public function create(): View
    {
        $company = auth()->user()->company;

        abort_unless($company, 403);

        return view('jobs.create', compact('company'));
    }

    public function store(StoreJobRequest $request): RedirectResponse
    {
        $company = auth()->user()->company;

        abort_unless($company, 403);

        $job = $company->jobs()->create($request->validated());

        return redirect()->route('jobs.show', $job)->with('success', 'Job created successfully.');
    }

    public function show(Request $request, Job $job): View
    {
        if ($request->user()?->can('view', $job) || $this->isPubliclyVisible($job)) {
            return view('jobs.show', compact('job'));
        }

        abort(403);
    }

    private function isPubliclyVisible(Job $job): bool
    {
        return $job->status === 'published' && ! $job->deadline->isBefore(today());
    }

    public function edit(Job $job): View
    {
        $this->authorize('update', $job);

        return view('jobs.edit', compact('job'));
    }

    public function duplicate(Job $job): RedirectResponse
    {
        $this->authorize('view', $job);

        $copy = $this->jobService->duplicate($job);

        return redirect()->route('jobs.edit', $copy)->with('success', 'Job duplicated as draft.');
    }

    public function trash(EmployerJobTrashRequest $request): View
    {
        $jobs = $this->jobService->trashedJobs(auth()->user());

        return view('jobs.trash', compact('jobs'));
    }

    public function restore(int $job): RedirectResponse
    {
        $job = Job::withTrashed()->findOrFail($job);

        $this->authorize('view', $job);

        $job->restore();

        return redirect()->route('jobs.trash')->with('success', 'Job restored successfully.');
    }

    public function forceDelete(int $job): RedirectResponse
    {
        $job = Job::withTrashed()->findOrFail($job);

        $this->authorize('view', $job);

        $job->forceDelete();

        return redirect()->route('jobs.trash')->with('success', 'Job permanently deleted.');
    }

    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $this->authorize('update', $job);

        $job->update($request->validated());

        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
