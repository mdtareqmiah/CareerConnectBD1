<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly AdminDashboardService $dashboardService)
    {
    }

    public function index(): View
    {
        $dashboard = $this->dashboardService->getDashboardData();

        return view('admin.dashboard', $dashboard);
    }
}
