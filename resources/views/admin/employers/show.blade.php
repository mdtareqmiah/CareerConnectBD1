@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Employer Details</h1>
        <div class="text-muted">{{ $employer->name }}</div>
    </div>
    <a href="{{ route('admin.employers.index') }}" class="btn btn-outline-primary">Back</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Employer Profile</h3>
                <div class="small text-muted mb-1">Name</div>
                <div class="mb-2">{{ $employer->name }}</div>
                <div class="small text-muted mb-1">Email</div>
                <div class="mb-2">{{ $employer->email }}</div>
                <div class="small text-muted mb-1">Account Status</div>
                <div class="mb-2">{{ $employer->is_active ? 'Active' : 'Inactive' }}</div>
                <div class="small text-muted mb-1">Last Login</div>
                <div>{{ $employer->last_login_at?->format('M d, Y h:i A') ?? 'Never' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Company</h3>
                @if($company)
                    <div class="small text-muted mb-1">Company Name</div>
                    <div class="mb-2">{{ $company->company_name }}</div>
                    <div class="small text-muted mb-1">Industry</div>
                    <div class="mb-2">{{ $company->industry }}</div>
                    <div class="small text-muted mb-1">Verification</div>
                    <div class="mb-2">{{ ucfirst($company->verification_status ?? 'pending') }}</div>
                    <div class="small text-muted mb-1">Status</div>
                    <div class="mb-3">{{ $company->is_active ? 'Active' : 'Inactive' }}</div>
                    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-outline-primary">View Company</a>
                @else
                    <div class="text-muted">No company profile yet.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Activity</h3>
                <div class="small text-muted mb-1">Jobs</div>
                <div class="mb-2">{{ $jobsCount }}</div>
                <div class="small text-muted mb-1">Applications</div>
                <div>{{ $applicationsCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h3 class="h6 mb-0">Recent Jobs</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Title</th><th>Status</th><th>Deadline</th><th>Created</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td><span class="badge bg-{{ $job->status_badge_color }}">{{ ucfirst($job->display_status) }}</span></td>
                    <td>{{ $job->deadline?->format('M d, Y') ?? 'N/A' }}</td>
                    <td>{{ $job->created_at?->format('M d, Y') }}</td>
                    <td class="text-end"><a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No jobs found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
