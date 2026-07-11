<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobSeekerApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:pending,reviewed,shortlisted,rejected,accepted'],
            'sort' => ['nullable', 'string', 'in:newest,oldest'],
        ]);

        $filters = array_merge([
            'search' => null,
            'status' => null,
            'sort' => 'newest',
        ], $validated);

        $query = JobApplication::query()
            ->where('user_id', $user->id)
            ->with(['job.company', 'resume'])
            ->when($filters['search'], function ($query, $search) {
                $query->whereHas('job', fn ($jobQuery) => $jobQuery->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('job.company', fn ($companyQuery) => $companyQuery->where('company_name', 'like', "%{$search}%"));
            })
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->orderBy('applied_at', $filters['sort'] === 'oldest' ? 'asc' : 'desc');

        $applications = $query->paginate(10)->appends($request->query());

        return view('job-seeker.applications.index', compact('applications', 'filters'));
    }

    public function show(JobApplication $jobApplication): View
    {
        $this->authorize('view', $jobApplication);

        $jobApplication->load(['job.company', 'resume']);

        return view('job-seeker.applications.show', compact('jobApplication'));
    }
}
