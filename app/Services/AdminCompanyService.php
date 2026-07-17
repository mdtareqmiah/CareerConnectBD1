<?php

namespace App\Services;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminCompanyService
{
    public function getCompanyListingData(array $filters): array
    {
        return [
            'companies' => $this->getCompanies($filters),
            'stats' => $this->getCompanyStats(),
        ];
    }

    public function getEmployerListingData(array $filters): array
    {
        return [
            'employers' => $this->getEmployers($filters),
            'stats' => $this->getEmployerStats(),
        ];
    }

    public function getCompanyDetails(Company $company): array
    {
        $company->load([
            'employer.role',
            'jobs' => fn ($query) => $query->latest('created_at')->limit(10),
        ]);

        return [
            'company' => $company,
            'jobsCount' => $company->jobs()->count(),
            'applicationsCount' => JobApplication::whereHas('job', fn ($query) => $query->where('company_id', $company->id))->count(),
        ];
    }

    public function getEmployerDetails(User $employer): array
    {
        $company = $employer->company;
        $jobsCount = 0;
        $applicationsCount = 0;

        if ($company) {
            $jobsCount = $company->jobs()->count();
            $applicationsCount = JobApplication::whereHas('job', fn ($query) => $query->where('company_id', $company->id))->count();
        }

        return [
            'employer' => $employer,
            'company' => $company,
            'jobsCount' => $jobsCount,
            'applicationsCount' => $applicationsCount,
            'jobs' => $company ? $company->jobs()->latest('created_at')->limit(10)->get() : collect(),
        ];
    }

    public function updateCompany(Company $company, array $data): Company
    {
        $company->update($data);

        return $company->refresh();
    }

    public function approveCompany(Company $company, User $admin): Company
    {
        $payload = [
            'verification_status' => 'verified',
            'is_active' => true,
        ];

        if (! $company->verified_at) {
            $payload['verified_at'] = now();
        }

        if (! $company->verified_by) {
            $payload['verified_by'] = $admin->id;
        }

        $company->update($payload);

        return $company->refresh();
    }

    public function rejectCompany(Company $company): Company
    {
        $company->update([
            'verification_status' => 'rejected',
            'is_active' => false,
        ]);

        return $company->refresh();
    }

    public function suspendCompany(Company $company): Company
    {
        $company->update(['is_active' => false]);

        return $company->refresh();
    }

    public function activateCompany(Company $company): Company
    {
        $company->update(['is_active' => true]);

        return $company->refresh();
    }

    private function getCompanies(array $filters): LengthAwarePaginator
    {
        $query = Company::query()->with('employer');

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhereHas('employer', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $verification = (string) ($filters['verification'] ?? '');
        if (in_array($verification, ['pending', 'verified', 'rejected'], true)) {
            $query->where('verification_status', $verification);
        }

        $status = (string) ($filters['status'] ?? '');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $sort = (string) ($filters['sort'] ?? 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->paginate(15)->withQueryString();
    }

    private function getEmployers(array $filters): LengthAwarePaginator
    {
        $employerRoleId = Role::where('slug', 'employer')->value('id');

        $query = User::query()
            ->with('company')
            ->when($employerRoleId, fn ($q) => $q->where('role_id', $employerRoleId));

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('company', fn ($q) => $q->where('company_name', 'like', "%{$search}%")->orWhere('industry', 'like', "%{$search}%"));
            });
        }

        $verification = (string) ($filters['verification'] ?? '');
        if (in_array($verification, ['pending', 'verified', 'rejected'], true)) {
            $query->whereHas('company', fn ($q) => $q->where('verification_status', $verification));
        }

        $status = (string) ($filters['status'] ?? '');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $sort = (string) ($filters['sort'] ?? 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->paginate(15)->withQueryString();
    }

    private function getCompanyStats(): array
    {
        $base = Company::query();

        return [
            'total_companies' => (clone $base)->count(),
            'verified' => (clone $base)->where('verification_status', 'verified')->count(),
            'pending' => (clone $base)->where('verification_status', 'pending')->count(),
            'rejected' => (clone $base)->where('verification_status', 'rejected')->count(),
            'suspended' => (clone $base)->where('is_active', false)->count(),
        ];
    }

    private function getEmployerStats(): array
    {
        $employerRoleId = Role::where('slug', 'employer')->value('id');

        $base = User::query()->when($employerRoleId, fn ($q) => $q->where('role_id', $employerRoleId));

        return [
            'total_employers' => (clone $base)->count(),
            'verified' => (clone $base)->whereHas('company', fn ($q) => $q->where('verification_status', 'verified'))->count(),
            'pending' => (clone $base)->whereHas('company', fn ($q) => $q->where('verification_status', 'pending'))->count(),
            'rejected' => (clone $base)->whereHas('company', fn ($q) => $q->where('verification_status', 'rejected'))->count(),
            'suspended' => (clone $base)->where('is_active', false)->count(),
        ];
    }
}
