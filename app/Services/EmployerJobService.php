<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EmployerJobService
{
    public function listJobs(User $employer, array $filters): LengthAwarePaginator
    {
        return $this->baseQuery($employer)
            ->search($filters['search'] ?? null)
            ->statusFilter($filters['status'] ?? null)
            ->sortBy($filters['sort'] ?? 'newest')
            ->paginate(10)
            ->appends(request()->query());
    }

    public function trashedJobs(User $employer): LengthAwarePaginator
    {
        return $this->baseQuery($employer)
            ->onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->appends(request()->query());
    }

    public function stats(User $employer): array
    {
        $base = $this->baseQuery($employer);

        return [
            'total_jobs' => $base->count(),
            'published_jobs' => (clone $base)->where('status', 'published')->count(),
            'draft_jobs' => (clone $base)->where('status', 'draft')->count(),
            'closed_jobs' => (clone $base)->where('status', 'archived')->count(),
            'expired_jobs' => (clone $base)
                ->where('status', 'published')
                ->where('deadline', '<', today())
                ->count(),
        ];
    }

    public function duplicate(Job $job): Job
    {
        $copy = $job->replicate();
        $copy->slug = null;
        $copy->status = 'draft';
        $copy->published_at = null;
        $copy->push();

        return $copy;
    }

    private function baseQuery(User $employer): Builder
    {
        return Job::with('company')
            ->forEmployer($employer);
    }
}
