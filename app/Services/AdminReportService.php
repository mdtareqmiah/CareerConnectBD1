<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ResumeBuilder;
use App\Models\Role;
use App\Models\User;

class AdminReportService
{
    public function getReportData(): array
    {
        return [
            'cards' => $this->getCards(),
            'monthly' => $this->getMonthlyData(),
            'usersByRole' => $this->getUsersByRole(),
            'companiesByIndustry' => $this->getCompaniesByIndustry(),
        ];
    }

    private function getCards(): array
    {
        return [
            'total_users' => User::count(),
            'total_employers' => User::whereHas('role', fn ($q) => $q->where('slug', 'employer'))->count(),
            'total_companies' => Company::count(),
            'total_jobs' => Job::count(),
            'published_jobs' => Job::where('status', 'published')->count(),
            'applications' => JobApplication::count(),
            'active_jobs' => Job::where('status', 'published')->whereDate('deadline', '>=', today())->count(),
            'closed_jobs' => Job::where('status', 'archived')->count(),
            'resume_builders' => ResumeBuilder::count(),
        ];
    }

    private function getMonthlyData(): array
    {
        $labels = [];
        $jobs = [];
        $applications = [];

        for ($offset = 11; $offset >= 0; $offset--) {
            $month = now()->startOfMonth()->subMonths($offset);
            $labels[] = $month->format('M Y');

            $jobs[] = Job::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $applications[] = JobApplication::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'labels' => $labels,
            'jobs' => $jobs,
            'applications' => $applications,
        ];
    }

    private function getUsersByRole(): array
    {
        return Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get()
            ->map(fn ($role) => [
                'role' => $role->name,
                'count' => $role->users_count,
            ])
            ->all();
    }

    private function getCompaniesByIndustry(): array
    {
        return Company::query()
            ->selectRaw('industry, COUNT(*) as total')
            ->groupBy('industry')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'industry' => $row->industry,
                'count' => (int) $row->total,
            ])
            ->all();
    }
}
