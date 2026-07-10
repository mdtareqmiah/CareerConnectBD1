<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store the company in database.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();
        $data['employer_id'] = auth()->user()->id;

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $this->storeCompanyLogo($request->file('company_logo'));
        }

        $company = Company::create($data);

        return redirect()->route('company.show', $company)->with('success', 'Company profile created successfully!');
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the company.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        return view('company.edit', compact('company'));
    }

    /**
     * Update the company in database.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $data = $request->validated();

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $this->storeCompanyLogo($request->file('company_logo'), $company->company_logo);
        }

        $company->update($data);

        return redirect()->route('company.show', $company)->with('success', 'Company profile updated successfully!');
    }

    /**
     * Delete the company.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        return redirect()->route('employer.dashboard')->with('success', 'Company profile deleted successfully!');
    }

    /**
     * Store the company logo to disk.
     */
    private function storeCompanyLogo($logo, ?string $previousLogo = null): string
    {
        // Delete previous logo if it exists
        if ($previousLogo && Storage::disk('public')->exists("company-logos/{$previousLogo}")) {
            Storage::disk('public')->delete("company-logos/{$previousLogo}");
        }

        $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
        $logo->storeAs('company-logos', $filename, 'public');

        return $filename;
    }
}
