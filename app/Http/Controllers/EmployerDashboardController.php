<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

        $jobStats = $company ? $this->jobService->stats($user) : ['total_jobs' => 0];

        $stats = [
            'job_postings' => $jobStats['total_jobs'] ?? 0,
            'total_applications' => 0,
            'profile_completion' => $company ? 100 : 0,
        ];

        $applicationStats = $company ? $this->jobService->applicationStats($user) : [
            'total_applications' => 0,
            'pending_applications' => 0,
            'reviewed_applications' => 0,
            'shortlisted_applications' => 0,
            'rejected_applications' => 0,
            'hired_applications' => 0,
        ];

        $stats['total_applications'] = $applicationStats['total_applications'] ?? 0;

        return view('employer.dashboard', compact('user', 'company', 'stats', 'applicationStats'));
    }
}
