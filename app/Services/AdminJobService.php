<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminJobService
{
    public function getListingData(array $filters): array
    {
        return [
            'jobs' => $this->getJobs($filters),
            'stats' => $this->getStats(),
            'jobTypes' => Job::query()->distinct()->orderBy('job_type')->pluck('job_type'),
        ];
    }

    public function getJobDetails(Job $job): array
    {
        $job->load(['company.employer', 'applications.user']);

        return [
            'job' => $job,
            'applicationsCount' => $job->applications()->count(),
        ];
    }

    public function publish(Job $job): Job
    {
        $payload = ['status' => 'published'];

        if (! $job->published_at) {
            $payload['published_at'] = now();
        }

        $job->update($payload);

        return $job->refresh();
    }

    public function unpublish(Job $job): Job
    {
        $job->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return $job->refresh();
    }

    public function close(Job $job): Job
    {
        $job->update([
            'status' => 'archived',
            'published_at' => null,
        ]);

        return $job->refresh();
    }

    public function reopen(Job $job): Job
    {
        $job->update([
            'status' => 'published',
            'published_at' => $job->published_at ?? now(),
        ]);

        return $job->refresh();
    }

    public function softDelete(Job $job): void
    {
        if (! $job->trashed()) {
            $job->delete();
        }
    }

    public function restore(Job $job): Job
    {
        if ($job->trashed()) {
            $job->restore();
        }

        return $job->refresh();
    }

    public function findJobIncludingTrashed(int $jobId): Job
    {
        return Job::withTrashed()->with(['company.employer'])->findOrFail($jobId);
    }

    private function getJobs(array $filters): LengthAwarePaginator
    {
        $query = Job::query()
            ->withTrashed()
            ->with(['company.employer'])
            ->withCount('applications');

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('company', fn ($q) => $q
                        ->where('company_name', 'like', "%{$search}%")
                        ->orWhereHas('employer', fn ($eq) => $eq
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")));
            });
        }

        $status = (string) ($filters['status'] ?? '');
        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif (in_array($status, ['draft', 'published', 'closed', 'expired'], true)) {
            $query->whereNull('deleted_at')->statusFilter($status);
        }

        $jobType = trim((string) ($filters['job_type'] ?? ''));
        if ($jobType !== '') {
            $query->where('job_type', $jobType);
        }

        $location = trim((string) ($filters['location'] ?? ''));
        if ($location !== '') {
            $query->where('location', 'like', "%{$location}%");
        }

        $sort = (string) ($filters['sort'] ?? 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->paginate(15)->withQueryString();
    }

    private function getStats(): array
    {
        $base = Job::query();

        return [
            'total_jobs' => (clone $base)->count(),
            'published' => (clone $base)->where('status', 'published')->whereDate('deadline', '>=', today())->count(),
            'draft' => (clone $base)->where('status', 'draft')->count(),
            'closed' => (clone $base)->where('status', 'archived')->count(),
            'expired' => (clone $base)->where('status', 'published')->whereDate('deadline', '<', today())->count(),
        ];
    }
}
