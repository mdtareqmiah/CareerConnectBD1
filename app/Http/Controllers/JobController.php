<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployerJobIndexRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\EmployerJobTrashRequest;
use App\Models\Company;
use App\Models\Job;
use App\Services\EmployerJobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    private EmployerJobService $jobService;

    public function __construct(EmployerJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index(EmployerJobIndexRequest $request): View
    {
        $jobs = $this->jobService->listJobs(auth()->user(), $request->validatedFilters());
        $stats = $this->jobService->stats(auth()->user());

        return view('jobs.index', compact('jobs', 'stats'));
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

    public function show(Job $job): View
    {
        $this->authorize('view', $job);

        return view('jobs.show', compact('job'));
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
