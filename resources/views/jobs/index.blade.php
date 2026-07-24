@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-0">My Jobs</h1>
        <p class="text-muted mb-0">Manage your company job postings.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('employer.jobs.trash') }}" class="btn btn-outline-secondary">
            View Trash
        </a>

        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
            Create New Job
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employer.jobs.index') }}" class="row g-3">
            <div class="col-md-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search title, location, type, status"
                >
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="text-muted small">Total Jobs</div>
            <div class="h4 mb-0">{{ $stats['total_jobs'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="text-muted small">Published Jobs</div>
            <div class="h4 mb-0">{{ $stats['published_jobs'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="text-muted small">Draft Jobs</div>
            <div class="h4 mb-0">{{ $stats['draft_jobs'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="text-muted small">Closed / Expired Jobs</div>
            <div class="h4 mb-0">
                {{ ($stats['closed_jobs'] ?? 0) + ($stats['expired_jobs'] ?? 0) }}
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        @forelse ($jobs as $job)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <h5 class="card-title mb-1">{{ $job->title }}</h5>

                            <p class="text-muted mb-1">
                                {{ $job->location }}
                                @if($job->job_type)
                                    · {{ $job->job_type }}
                                @endif
                                · {{ ucfirst($job->display_status) }}
                            </p>

                            @if($job->deadline)
                                <p class="mb-1">
                                    Deadline: {{ $job->deadline->format('F j, Y') }}
                                </p>
                            @endif

                            <span class="badge bg-{{ $job->status_badge_color }} text-uppercase">
                                {{ $job->display_status }}
                            </span>
                        </div>

                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>

                            <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>

                            <form action="{{ route('employer.jobs.duplicate', $job) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-info">
                                    Duplicate
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                You have no jobs yet. Start by creating a new job posting.
            </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>

@endsection