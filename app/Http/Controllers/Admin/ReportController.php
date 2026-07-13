<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminReportService;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly AdminReportService $adminReportService)
    {
    }

    public function index(): View
    {
        $data = $this->adminReportService->getReportData();

        return view('admin.reports.index', $data);
    }
}
