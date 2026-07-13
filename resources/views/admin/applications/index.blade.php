@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Application Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Applications</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total</div><div class="h5 mb-0">{{ $stats['total'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Pending</div><div class="h5 mb-0">{{ $stats['pending'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Reviewed</div><div class="h5 mb-0">{{ $stats['reviewed'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Shortlisted</div><div class="h5 mb-0">{{ $stats['shortlisted'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Accepted</div><div class="h5 mb-0">{{ $stats['accepted'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Rejected</div><div class="h5 mb-0">{{ $stats['rejected'] }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-6">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Candidate, employer, company, job, location">
            </div>
            <div class="col-lg-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                    <option value="reviewed" @selected(($filters['status'] ?? '') === 'reviewed')>Reviewed</option>
                    <option value="shortlisted" @selected(($filters['status'] ?? '') === 'shortlisted')>Shortlisted</option>
                    <option value="interview" @selected(($filters['status'] ?? '') === 'interview')>Interview</option>
                    <option value="accepted" @selected(($filters['status'] ?? '') === 'accepted')>Accepted</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Sort</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest</option>
                </select>
            </div>
            <div class="col-lg-1 d-grid"><button class="btn btn-outline-primary" type="submit">Go</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Candidate</th>
                    <th>Employer</th>
                    <th>Company</th>
                    <th>Job</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th>Resume</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>{{ $application->user?->name ?? 'N/A' }}</td>
                    <td>
                        @if($application->job?->company?->employer)
                            <a href="{{ route('admin.employers.show', $application->job->company->employer) }}">{{ $application->job->company->employer->name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($application->job?->company)
                            <a href="{{ route('admin.companies.show', $application->job->company) }}">{{ $application->job->company->company_name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($application->job)
                            <a href="{{ route('admin.jobs.show', $application->job->id) }}">{{ $application->job->title }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $application->applied_at?->format('M d, Y') ?? $application->created_at?->format('M d, Y') }}</td>
                    <td><span class="badge bg-{{ $application->status_badge_color }}">{{ $application->status_label }}</span></td>
                    <td>
                        @if($application->resume)
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.applications.resume.preview', $application) }}" class="btn btn-sm btn-outline-primary">Preview</a>
                                <a href="{{ route('admin.applications.resume.download', $application) }}" class="btn btn-sm btn-outline-secondary">Download</a>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-outline-dark">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No applications found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div class="card-footer bg-white">{{ $applications->links() }}</div>
    @endif
</div>
@endsection
