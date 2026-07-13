@extends('layouts.admin')

@php
    $growthLabels = $growth['labels'] ?? [];
    $monthlyRegistrations = $growth['registrations'] ?? [];
    $monthlyJobPosts = $growth['job_posts'] ?? [];
    $monthlyApplications = $growth['applications'] ?? [];
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Admin Dashboard</h1>
        <p class="text-muted mb-0">System overview, growth trends, and recent activity.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @foreach($quickActions as $action)
            <a href="{{ $action['url'] }}" class="btn btn-sm btn-outline-primary">{{ $action['label'] }}</a>
        @endforeach
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Total Users</div><div class="h4 mb-0">{{ $stats['total_users'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Total Employers</div><div class="h4 mb-0">{{ $stats['total_employers'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Total Job Seekers</div><div class="h4 mb-0">{{ $stats['total_job_seekers'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Total Companies</div><div class="h4 mb-0">{{ $stats['total_companies'] }}</div></div></div></div>

    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Total Jobs</div><div class="h4 mb-0">{{ $stats['total_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Published Jobs</div><div class="h4 mb-0">{{ $stats['published_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Draft Jobs</div><div class="h4 mb-0">{{ $stats['draft_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Closed Jobs</div><div class="h4 mb-0">{{ $stats['closed_jobs'] }}</div></div></div></div>

    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Applications</div><div class="h4 mb-0">{{ $stats['applications'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Today's Registrations</div><div class="h4 mb-0">{{ $stats['todays_registrations'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Today's Jobs</div><div class="h4 mb-0">{{ $stats['todays_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card shadow-sm border-0"><div class="card-body"><div class="text-muted small">Today's Applications</div><div class="h4 mb-0">{{ $stats['todays_applications'] }}</div></div></div></div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h2 class="h5 mb-3">Growth Statistics</h2>
        <canvas id="growthChart" height="110"></canvas>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest Users</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Name</th><th>Role</th><th>Joined</th></tr></thead>
                        <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->role?->name ?? 'N/A' }}</td>
                                <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest Employers</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Name</th><th>Email</th><th>Company</th></tr></thead>
                        <tbody>
                        @forelse($recentEmployers as $employer)
                            <tr>
                                <td>{{ $employer->name }}</td>
                                <td>{{ $employer->email }}</td>
                                <td>{{ $employer->company?->company_name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest Companies</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Company</th><th>Employer</th><th>Created</th></tr></thead>
                        <tbody>
                        @forelse($recentCompanies as $company)
                            <tr>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->employer?->name ?? 'N/A' }}</td>
                                <td>{{ $company->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest Jobs</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Title</th><th>Status</th><th>Created</th></tr></thead>
                        <tbody>
                        @forelse($recentJobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ ucfirst($job->status) }}</td>
                                <td>{{ $job->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest Applications</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Applicant</th><th>Job</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                        @forelse($recentApplications as $application)
                            <tr>
                                <td>{{ $application->user?->name ?? 'N/A' }}</td>
                                <td>{{ $application->job?->title ?? 'N/A' }}</td>
                                <td>{{ ucfirst($application->status) }}</td>
                                <td>{{ $application->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const growthLabels = @json($growthLabels);
    const registrations = @json($monthlyRegistrations);
    const jobPosts = @json($monthlyJobPosts);
    const applications = @json($monthlyApplications);

    const chartElement = document.getElementById('growthChart');
    if (chartElement) {
        new Chart(chartElement, {
            type: 'line',
            data: {
                labels: growthLabels,
                datasets: [
                    {
                        label: 'Monthly Registrations',
                        data: registrations,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.15)',
                        tension: 0.3
                    },
                    {
                        label: 'Monthly Job Posts',
                        data: jobPosts,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.15)',
                        tension: 0.3
                    },
                    {
                        label: 'Monthly Applications',
                        data: applications,
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253, 126, 20, 0.15)',
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
