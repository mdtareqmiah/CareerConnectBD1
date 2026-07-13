<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as PaginatorLengthAware;
use Illuminate\Pagination\Paginator;

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

    public function listApplications(User $employer, array $filters): LengthAwarePaginator
    {
        $query = $this->applicationQuery($employer)
            ->with([
                'job.company',
                'user.jobSeekerProfile.skills',
                'user.jobSeekerProfile.educations',
                'user.jobSeekerProfile.resumes',
                'resume',
            ]);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['job_id'])) {
            $query->where('job_id', $filters['job_id']);
        }

        if (! empty($filters['search'])) {
            $query->whereHas('user', fn ($query) => $query->where('name', 'like', '%' . $filters['search'] . '%'));
        }

        $applications = $query->get();
        $matchService = app(CandidateMatchService::class);

        $applications->each(function ($application) use ($matchService) {
            $application->match_score = $matchService->calculate(
                $application->job,
                $application->user->jobSeekerProfile ?? new \App\Models\JobSeekerProfile()
            );
        });

        $sort = $filters['sort'] ?? null;

        if ($sort === 'oldest') {
            $applications = $applications->sortBy('applied_at');
        } elseif ($sort === 'newest') {
            $applications = $applications->sortByDesc('applied_at');
        } else {
            $applications = $applications->sortByDesc('match_score');
        }

        $page = Paginator::resolveCurrentPage();
        $perPage = 10;
        $items = $applications->forPage($page, $perPage)->values();
        $paginator = new PaginatorLengthAware($items, $applications->count(), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return $paginator;
    }

    public function applicationJobs(User $employer)
    {
        return Job::query()
            ->whereHas('company', fn ($query) => $query->where('employer_id', $employer->id))
            ->orderBy('title')
            ->get();
    }

    public function applicationStats(User $employer): array
    {
        $base = $this->applicationQuery($employer);

        return [
            'total_applications' => $base->count(),
            'pending_applications' => (clone $base)->where('status', 'pending')->count(),
            'reviewed_applications' => (clone $base)->where('status', 'reviewed')->count(),
            'shortlisted_applications' => (clone $base)->where('status', 'shortlisted')->count(),
            'rejected_applications' => (clone $base)->where('status', 'rejected')->count(),
            'hired_applications' => (clone $base)->where('status', 'accepted')->count(),
        ];
    }

    private function applicationQuery(User $employer)
    {
        return JobApplication::query()->whereHas('job.company', fn ($query) => $query->where('employer_id', $employer->id));
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
