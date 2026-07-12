@extends('layouts.app')

@section('title', 'Applications')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-inbox-fill"></i>
                <span class="fw-semibold">Employer inbox</span>
            </div>
            <h1 class="h3 mb-1">Applications</h1>
            <p class="text-muted mb-0">Review applications submitted for your job postings.</p>
        </div>
    </div>

    <div class="card border-0 shadow-soft mb-4">
        <div class="card-body p-4">
            <form class="row gx-2 gy-3" method="GET" action="{{ route('employer.applications.index') }}">
                <div class="col-md-3">
                    <label class="form-label">Job</label>
                    <select class="form-select" name="job_id">
                        <option value="">All jobs</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All statuses</option>
                        @foreach(['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Applicant name or email">
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label d-none">Apply</label>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-soft overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>
                                <div>{{ $application->user->name }}</div>
                                <small class="text-muted">{{ $application->user->email }}</small>
                            </td>
                            <td>{{ $application->job->title }}</td>
                            <td>{!! $application->statusBadge() !!}</td>
                            <td>{{ $application->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('employer.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="fw-semibold">No applications found</div>
                                <div class="text-muted small">Try a different filter to surface more candidates.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-transparent border-0 d-flex justify-content-end py-3">
            {{ $applications->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
