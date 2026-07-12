@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Applications</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-send-check"></i>
                <span class="fw-semibold">Applications</span>
            </div>
            <h1 class="h3 mb-1">My applications</h1>
            <p class="text-muted mb-0">Track your job applications, status updates, and resume submissions.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse jobs</a>
    </div>

    <div class="card border-0 shadow-soft mb-4">
        <div class="card-body">
            <form class="row gx-3 gy-3" method="GET" action="{{ route('job-seeker.applications.index') }}">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Job title or company name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All</option>
                        @foreach(['pending' => 'Pending', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected', 'accepted' => 'Hired'] as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort</label>
                    <select class="form-select" name="sort">
                        <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label d-none">Apply</label>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="card border-0 shadow-soft overflow-hidden py-5">
            <div class="card-body text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                    <span class="display-6">📭</span>
                </div>
                <h2 class="h5">No applications yet.</h2>
                <p class="text-muted">Browse jobs and submit your first application.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse jobs</a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-soft">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Cover Letter</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $application->job->title }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $application->job->company->company_logo_url }}" alt="{{ $application->job->company->company_name }}" class="rounded-circle" width="40" height="40">
                                        <div>
                                            <div>{{ $application->job->company->company_name }}</div>
                                            <div class="small text-muted">{{ $application->job->company->industry }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                <td>{!! $application->statusBadge() !!}</td>
                                <td>{{ $application->resume->file_name ?? basename($application->resume->file_path ?? '') }}</td>
                                <td>{{ Str::limit($application->cover_letter, 80) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('job-seeker.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
