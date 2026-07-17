@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Job Details</h1>
        <div class="text-muted">{{ $job->title }}</div>
    </div>
    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-primary">Back</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 mb-3">Job Information</h2>
                <div class="row g-3">
                    <div class="col-md-6"><div class="small text-muted">Title</div><div>{{ $job->title }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Location</div><div>{{ $job->location }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Job Type</div><div>{{ $job->job_type }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Workplace</div><div>{{ $job->workplace }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Employment Status</div><div>{{ $job->employment_status }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Deadline</div><div>{{ $job->deadline?->format('M d, Y') }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Salary</div><div>{{ number_format((int) $job->salary_min) }} - {{ number_format((int) $job->salary_max) }} ({{ $job->salary_type }})</div></div>
                    <div class="col-md-6"><div class="small text-muted">Applications</div><div>{{ $applicationsCount }}</div></div>
                    <div class="col-md-6"><div class="small text-muted">Views Count</div><div>{{ $job->views_count ?? 'N/A' }}</div></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><h3 class="h6">Description</h3><p class="mb-0">{{ $job->description }}</p></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><h3 class="h6">Requirements</h3><p class="mb-0">{{ $job->requirements }}</p></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body"><h3 class="h6">Responsibilities</h3><p class="mb-0">{{ $job->responsibilities }}</p></div></div>
        <div class="card border-0 shadow-sm"><div class="card-body"><h3 class="h6">Benefits</h3><p class="mb-0">{{ $job->benefits ?: 'N/A' }}</p></div></div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 mb-3">Company & Employer</h2>
                <div class="small text-muted">Company</div>
                <div class="mb-2">
                    @if($job->company)
                        <a href="{{ route('admin.companies.show', $job->company) }}">{{ $job->company->company_name }}</a>
                    @else
                        N/A
                    @endif
                </div>
                <div class="small text-muted">Employer</div>
                <div>
                    @if($job->company?->employer)
                        <a href="{{ route('admin.employers.show', $job->company->employer) }}">{{ $job->company->employer->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 mb-3">Actions</h2>
                <div class="d-grid gap-2">
                    @if(! $job->trashed())
                        @if($job->status !== 'published')
                            <form method="POST" action="{{ route('admin.jobs.publish', $job->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline-success w-100">Publish</button></form>
                        @endif
                        @if($job->status === 'published')
                            <form method="POST" action="{{ route('admin.jobs.unpublish', $job->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline-secondary w-100">Unpublish</button></form>
                            <form method="POST" action="{{ route('admin.jobs.close', $job->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline-warning w-100">Close</button></form>
                        @endif
                        @if($job->status === 'archived')
                            <form method="POST" action="{{ route('admin.jobs.reopen', $job->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline-success w-100">Reopen</button></form>
                        @endif
                        <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger w-100">Soft Delete</button></form>
                    @else
                        <form method="POST" action="{{ route('admin.jobs.restore', $job->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline-success w-100">Restore</button></form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" id="applications">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="h6 mb-0">Applications</h3>
        <span class="text-muted small">Total {{ $applicationsCount }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Candidate</th><th>Status</th><th>Applied</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($job->applications as $application)
                <tr>
                    <td>{{ $application->user?->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-{{ $application->status_badge_color }}">{{ $application->status_label }}</span></td>
                    <td>{{ $application->applied_at?->format('M d, Y h:i A') ?? $application->created_at?->format('M d, Y h:i A') }}</td>
                    <td class="text-end"><a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No applications found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
