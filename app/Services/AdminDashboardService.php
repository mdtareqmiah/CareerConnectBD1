<?php

namespace App\Services;

use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\Feedback;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SupportTicket;
use App\Models\User;

class AdminDashboardService
{
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getStats(),
            'recentUsers' => $this->getRecentUsers(),
            'recentEmployers' => $this->getRecentEmployers(),
            'recentCompanies' => $this->getRecentCompanies(),
            'recentJobs' => $this->getRecentJobs(),
            'recentApplications' => $this->getRecentApplications(),
            'growth' => $this->getGrowthStats(),
            'quickActions' => $this->getQuickActions(),
        ];
    }

    private function getStats(): array
    {
        $today = now()->toDateString();

        return [
            'total_users' => User::count(),
            'total_employers' => User::whereHas('role', fn ($query) => $query->where('slug', 'employer'))->count(),
            'total_job_seekers' => User::whereHas('role', fn ($query) => $query->where('slug', 'job-seeker'))->count(),
            'total_companies' => Company::count(),
            'total_jobs' => Job::count(),
            'published_jobs' => Job::where('status', 'published')->count(),
            'draft_jobs' => Job::where('status', 'draft')->count(),
            'closed_jobs' => Job::where('status', 'archived')->count(),
            'applications' => JobApplication::count(),
            'todays_registrations' => User::whereDate('created_at', $today)->count(),
            'todays_jobs' => Job::whereDate('created_at', $today)->count(),
            'todays_applications' => JobApplication::whereDate('created_at', $today)->count(),
            'total_contacts' => ContactMessage::count(),
            'unread_contacts' => ContactMessage::where('status', 'unread')->count(),
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'pending_tickets' => SupportTicket::where('status', 'pending')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'resolved')->count(),
            'total_feedback' => Feedback::count(),
            'average_feedback_rating' => round((float) Feedback::avg('rating'), 2),
        ];
    }

    private function getRecentUsers()
    {
        return User::with('role')
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'name', 'email', 'role_id', 'created_at']);
    }

    private function getRecentEmployers()
    {
        return User::with('company')
            ->whereHas('role', fn ($query) => $query->where('slug', 'employer'))
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'name', 'email', 'created_at']);
    }

    private function getRecentCompanies()
    {
        return Company::with('employer')
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'company_name', 'employer_id', 'created_at']);
    }

    private function getRecentJobs()
    {
        return Job::with('company')
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'company_id', 'title', 'status', 'created_at']);
    }

    private function getRecentApplications()
    {
        return JobApplication::with(['user', 'job'])
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'job_id', 'user_id', 'status', 'created_at']);
    }

    private function getGrowthStats(): array
    {
        $labels = [];
        $registrations = [];
        $jobPosts = [];
        $applications = [];
        $contacts = [];
        $feedback = [];
        $tickets = [];

        for ($offset = 11; $offset >= 0; $offset--) {
            $month = now()->startOfMonth()->subMonths($offset);
            $labels[] = $month->format('M Y');

            $registrations[] = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $jobPosts[] = Job::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $applications[] = JobApplication::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $contacts[] = ContactMessage::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $feedback[] = Feedback::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $tickets[] = SupportTicket::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'labels' => $labels,
            'registrations' => $registrations,
            'job_posts' => $jobPosts,
            'applications' => $applications,
            'contacts' => $contacts,
            'feedback' => $feedback,
            'tickets' => $tickets,
        ];
    }

    private function getQuickActions(): array
    {
        return [
            ['label' => 'Create Job', 'url' => route('admin.jobs.index')],
            ['label' => 'View Companies', 'url' => route('admin.companies.index')],
            ['label' => 'Manage Users', 'url' => route('admin.users.index')],
            ['label' => 'Reports', 'url' => route('admin.reports.index')],
            ['label' => 'Communication Center', 'url' => route('admin.communications.index')],
        ];
    }
}
