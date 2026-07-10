<?php

namespace App\Http\Controllers;

use App\Models\Company;

class EmployerDashboardController extends Controller
{
    /**
     * Display the employer dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        // Calculate statistics
        $stats = [
            'job_postings' => 0, // Can be extended when job module is added
            'total_applications' => 0, // Can be extended when applications module is added
            'profile_completion' => $company ? 100 : 0, // Company exists = complete
        ];

        return view('employer.dashboard', compact('user', 'company', 'stats'));
    }
}
