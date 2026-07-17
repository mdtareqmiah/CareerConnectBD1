<?php

namespace App\Services;

use App\Models\JobApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminApplicationService
{
    public function getListingData(array $filters): array
    {
        return [
            'applications' => $this->getApplications($filters),
            'stats' => $this->getStats(),
        ];
    }

    public function getApplicationDetails(JobApplication $application): JobApplication
    {
        return $application->load([
            'job.company.employer',
            'user.jobSeekerProfile.educations',
            'user.jobSeekerProfile.experiences',
            'user.jobSeekerProfile.skills',
            'resume',
        ]);
    }

    public function mapStatusFilter(?string $status): ?string
    {
        if (! $status) {
            return null;
        }

        if ($status === 'interview') {
            return 'shortlisted';
        }

        return $status;
    }

    private function getApplications(array $filters): LengthAwarePaginator
    {
        $query = JobApplication::query()->with([
            'job.company.employer',
            'user.jobSeekerProfile',
            'resume',
        ]);

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('job', fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('location', 'like', "%{$search}%"))
                    ->orWhereHas('job.company', fn ($q) => $q->where('company_name', 'like', "%{$search}%"))
                    ->orWhereHas('job.company.employer', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $status = $this->mapStatusFilter((string) ($filters['status'] ?? ''));
        if ($status && in_array($status, ['pending', 'reviewed', 'shortlisted', 'accepted', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $sort = (string) ($filters['sort'] ?? 'newest');
        if ($sort === 'oldest') {
            $query->orderByRaw('COALESCE(applied_at, created_at) asc');
        } else {
            $query->orderByRaw('COALESCE(applied_at, created_at) desc');
        }

        return $query->paginate(15)->withQueryString();
    }

    private function getStats(): array
    {
        $base = JobApplication::query();

        return [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'reviewed' => (clone $base)->where('status', 'reviewed')->count(),
            'shortlisted' => (clone $base)->where('status', 'shortlisted')->count(),
            'accepted' => (clone $base)->where('status', 'accepted')->count(),
            'rejected' => (clone $base)->where('status', 'rejected')->count(),
        ];
    }
}
