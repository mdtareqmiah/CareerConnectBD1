@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-0">Job Listings</h1>
        <p class="text-muted mb-0">Browse current published jobs from top employers.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('jobs.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search title, company, location">
            </div>
            <div class="col-md-2">
                <select name="job_type" class="form-select">
                    <option value="">All Employment Types</option>
                    @foreach($jobTypes as $type)
                        <option value="{{ $type }}" {{ request('job_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="salary_min" value="{{ request('salary_min') }}" min="0" class="form-control" placeholder="Min salary">
            </div>
            <div class="col-md-2">
                <input type="number" name="salary_max" value="{{ request('salary_max') }}" min="0" class="form-control" placeholder="Max salary">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-2">
                <select name="recommended" class="form-select">
                    <option value="">All jobs</option>
                    <option value="1" {{ request('recommended') === '1' ? 'selected' : '' }}>Recommended only</option>
                </select>
            </div>
            <div class="col-12 text-end">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 g-4">
    @forelse ($jobs as $job)
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <h5 class="card-title mb-1">{{ $job->title }}</h5>
                        <div class="text-muted small">{{ $job->company->company_name }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-success text-uppercase me-2">{{ $job->job_type }}</span>
                        <span class="badge bg-secondary text-uppercase">{{ $job->employment_status }}</span>
                    </div>

                    <p class="mb-1 text-muted">{{ $job->location }}</p>
                    <p class="mb-3"><strong>Salary:</strong> {{ $job->salary_type }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</p>

                    <div class="mt-auto">
                        <p class="mb-1 text-muted small">Deadline: {{ $job->deadline->format('F j, Y') }}</p>
                        <p class="mb-3 text-muted small">Published: {{ $job->published_at?->format('F j, Y') ?? 'N/A' }}</p>
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 text-center">
                <div class="mb-3">
                    <span class="fs-1">📭</span>
                </div>
                <h5 class="card-title">No jobs match your filters</h5>
                <p class="text-muted mb-0">Try adjusting your search, employment type, or salary range.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection
