<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\EmployerJobService;

class EmployerDashboardController extends Controller
{
    private EmployerJobService $jobService;

    public function __construct(EmployerJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Display the employer dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        $company = $user->company;

        /*
        |--------------------------------------------------------------------------
        | Job Posting Count
        |--------------------------------------------------------------------------
        */

        $jobPostings = $company
            ? Job::where('company_id', $company->id)->count()
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Job Statistics
        |--------------------------------------------------------------------------
        */

        $jobStats = $company
            ? $this->jobService->stats($user)
            : [
                'total_jobs' => 0,
                'published_jobs' => 0,
                'draft_jobs' => 0,
                'closed_jobs' => 0,
                'expired_jobs' => 0,
            ];


        /*
        |--------------------------------------------------------------------------
        | Recent Jobs
        |--------------------------------------------------------------------------
        */

        $recentJobs = $company
            ? Job::where('company_id', $company->id)
                ->latest('created_at')
                ->take(5)
                ->get()
            : collect();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'job_postings' => $jobPostings,

            'total_applications' => 0,

            'profile_completion' => $company ? 100 : 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | Application Statistics
        |--------------------------------------------------------------------------
        */

        $applicationStats = $company
            ? $this->jobService->applicationStats($user)
            : [
                'total_applications' => 0,
                'pending_applications' => 0,
                'reviewed_applications' => 0,
                'shortlisted_applications' => 0,
                'rejected_applications' => 0,
                'hired_applications' => 0,
            ];


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'employer.dashboard',
            compact(
                'user',
                'company',
                'stats',
                'jobStats',
                'applicationStats',
                'recentJobs'
            )
        );
    }
}