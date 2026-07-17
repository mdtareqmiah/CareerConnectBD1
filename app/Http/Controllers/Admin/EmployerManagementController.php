<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminCompanyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployerManagementController extends Controller
{
    public function __construct(private readonly AdminCompanyService $adminCompanyService)
    {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'verification' => $request->string('verification')->toString(),
            'status' => $request->string('status')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];

        $data = $this->adminCompanyService->getEmployerListingData($filters);

        return view('admin.employers.index', [
            'employers' => $data['employers'],
            'stats' => $data['stats'],
            'filters' => $filters,
        ]);
    }

    public function show(User $employer): View
    {
        $details = $this->adminCompanyService->getEmployerDetails($employer->load(['role', 'company']));

        return view('admin.employers.show', $details);
    }
}
