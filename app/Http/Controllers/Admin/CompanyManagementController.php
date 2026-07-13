<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateManagedCompanyRequest;
use App\Models\Company;
use App\Services\AdminCompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyManagementController extends Controller
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

        $data = $this->adminCompanyService->getCompanyListingData($filters);

        return view('admin.companies.index', [
            'companies' => $data['companies'],
            'stats' => $data['stats'],
            'filters' => $filters,
        ]);
    }

    public function show(Company $company): View
    {
        $details = $this->adminCompanyService->getCompanyDetails($company);

        return view('admin.companies.show', $details);
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(UpdateManagedCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->adminCompanyService->updateCompany($company, $request->validated());

        return redirect()->route('admin.companies.show', $company)->with('success', 'Company updated successfully.');
    }

    public function approve(Request $request, Company $company): RedirectResponse
    {
        $this->adminCompanyService->approveCompany($company, $request->user());

        return back()->with('success', 'Company approved successfully.');
    }

    public function reject(Company $company): RedirectResponse
    {
        $this->adminCompanyService->rejectCompany($company);

        return back()->with('success', 'Company rejected successfully.');
    }

    public function suspend(Company $company): RedirectResponse
    {
        $this->adminCompanyService->suspendCompany($company);

        return back()->with('success', 'Company suspended successfully.');
    }

    public function activate(Company $company): RedirectResponse
    {
        $this->adminCompanyService->activateCompany($company);

        return back()->with('success', 'Company activated successfully.');
    }
}
