@extends('layouts.app')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-search"></i>
                <span class="fw-semibold">Discover opportunities</span>
            </div>
            <h1 class="h2 mb-2">Browse current openings</h1>
            <p class="text-muted mb-0">Explore the latest published roles from employers across the platform.</p>
        </div>
        <div class="text-muted small">Showing {{ $jobs->count() }} result{{ $jobs->count() === 1 ? '' : 's' }}</div>
    </div>
</div>

<div class="card border-0 shadow-soft mb-4 overflow-hidden">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('jobs.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-lg-4">
                <label class="form-label small fw-semibold text-muted">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title, company, location">
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label small fw-semibold text-muted">Type</label>
                <select name="job_type" class="form-select">
                    <option value="">All types</option>
                    @foreach($jobTypes as $type)
                        <option value="{{ $type }}" {{ request('job_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label small fw-semibold text-muted">Min salary</label>
                <input type="number" name="salary_min" value="{{ request('salary_min') }}" min="0" class="form-control" placeholder="Min">
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label small fw-semibold text-muted">Max salary</label>
                <input type="number" name="salary_max" value="{{ request('salary_max') }}" min="0" class="form-control" placeholder="Max">
            </div>
            <div class="col-12 col-sm-6 col-lg-2 d-grid">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Recommended</label>
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

<div class="row row-cols-1 row-cols-lg-2 g-4">
    @forelse ($jobs as $job)
        <div class="col">
            <div class="card h-100 shadow-soft border-0">
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $job->title }}</h5>
                            <div class="text-muted small fw-semibold">{{ $job->company->company_name }}</div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">{{ $job->job_type }}</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark border">{{ $job->employment_status }}</span>
                        <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i>{{ $job->location }}</span>
                    </div>

                    <p class="mb-3 text-muted">{{ $job->salary_type }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</p>

                    <div class="mt-auto">
                        <p class="mb-1 text-muted small"><i class="bi bi-calendar3 me-1"></i>Deadline: {{ $job->deadline->format('F j, Y') }}</p>
                        <p class="mb-3 text-muted small"><i class="bi bi-send me-1"></i>Published: {{ $job->published_at?->format('F j, Y') ?? 'N/A' }}</p>
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-primary">View details</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-soft p-4 p-lg-5 text-center">
                <div class="mb-3">
                    <span class="display-6"><i class="bi bi-search"></i></span>
                </div>
                <h5 class="card-title">No jobs match your filters</h5>
                <p class="text-muted mb-0">Try adjusting your search, employment type, or salary range to see more opportunities.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection
