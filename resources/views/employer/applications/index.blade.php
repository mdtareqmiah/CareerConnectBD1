@extends('layouts.app')

@section('title', 'Applications')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Applications</h1>
            <p class="text-muted">Review applications submitted for your job postings.</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
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

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job</th>
                        <th>Status</th>
                        <th>Match Score</th>
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
                            <td>
                                <div class="fw-semibold">{{ $application->match_score ?? 0 }}%</div>
                                <small class="text-muted">Candidate Match</small>
                            </td>
                            <td>{{ $application->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('employer.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-end">
            {{ $applications->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
