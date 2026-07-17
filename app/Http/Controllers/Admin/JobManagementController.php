<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\AdminJobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobManagementController extends Controller
{
    public function __construct(private readonly AdminJobService $adminJobService)
    {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'job_type' => $request->string('job_type')->toString(),
            'location' => $request->string('location')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];

        $data = $this->adminJobService->getListingData($filters);

        return view('admin.jobs.index', [
            'jobs' => $data['jobs'],
            'stats' => $data['stats'],
            'jobTypes' => $data['jobTypes'],
            'filters' => $filters,
        ]);
    }

    public function show(int $jobId): View
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);

        return view('admin.jobs.show', $this->adminJobService->getJobDetails($job));
    }

    public function publish(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->publish($job);

        return back()->with('success', 'Job published successfully.');
    }

    public function unpublish(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->unpublish($job);

        return back()->with('success', 'Job unpublished successfully.');
    }

    public function close(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->close($job);

        return back()->with('success', 'Job closed successfully.');
    }

    public function reopen(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->reopen($job);

        return back()->with('success', 'Job reopened successfully.');
    }

    public function destroy(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->softDelete($job);

        return back()->with('success', 'Job deleted successfully.');
    }

    public function restore(int $jobId): RedirectResponse
    {
        $job = $this->adminJobService->findJobIncludingTrashed($jobId);
        $this->adminJobService->restore($job);

        return back()->with('success', 'Job restored successfully.');
    }
}
