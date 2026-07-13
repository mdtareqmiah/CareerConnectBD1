@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Job Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jobs</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Jobs</div><div class="h5 mb-0">{{ $stats['total_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Published</div><div class="h5 mb-0">{{ $stats['published'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Draft</div><div class="h5 mb-0">{{ $stats['draft'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Closed</div><div class="h5 mb-0">{{ $stats['closed'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Expired</div><div class="h5 mb-0">{{ $stats['expired'] }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Title, company, employer, location">
            </div>
            <div class="col-lg-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                    <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>Closed</option>
                    <option value="expired" @selected(($filters['status'] ?? '') === 'expired')>Expired</option>
                    <option value="deleted" @selected(($filters['status'] ?? '') === 'deleted')>Deleted</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Job Type</label>
                <select name="job_type" class="form-select">
                    <option value="">All</option>
                    @foreach($jobTypes as $jobType)
                        <option value="{{ $jobType }}" @selected(($filters['job_type'] ?? '') === $jobType)>{{ $jobType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $filters['location'] ?? '' }}" placeholder="Location">
            </div>
            <div class="col-lg-1">
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
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Employer</th>
                    <th>Location</th>
                    <th>Job Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Applications</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->title }}</td>
                    <td>
                        @if($job->company)
                            <a href="{{ route('admin.companies.show', $job->company) }}">{{ $job->company->company_name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($job->company?->employer)
                            <a href="{{ route('admin.employers.show', $job->company->employer) }}">{{ $job->company->employer->name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $job->location }}</td>
                    <td>{{ $job->job_type }}</td>
                    <td>{{ $job->deadline?->format('M d, Y') }}</td>
                    <td>
                        @if($job->trashed())
                            <span class="badge bg-dark">Deleted</span>
                        @else
                            <span class="badge bg-{{ $job->status_badge_color }}">{{ ucfirst($job->display_status) }}</span>
                        @endif
                    </td>
                    <td>{{ $job->applications_count }}</td>
                    <td>{{ $job->created_at?->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1 flex-wrap">
                            <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.jobs.show', $job->id) }}#applications" class="btn btn-sm btn-outline-dark">Applications</a>
                            @if(! $job->trashed())
                                @if($job->status !== 'published')
                                    <form method="POST" action="{{ route('admin.jobs.publish', $job->id) }}" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-success">Publish</button></form>
                                @endif
                                @if($job->status === 'published')
                                    <form method="POST" action="{{ route('admin.jobs.unpublish', $job->id) }}" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-secondary">Unpublish</button></form>
                                    <form method="POST" action="{{ route('admin.jobs.close', $job->id) }}" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-warning">Close</button></form>
                                @endif
                                @if($job->status === 'archived')
                                    <form method="POST" action="{{ route('admin.jobs.reopen', $job->id) }}" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-success">Reopen</button></form>
                                @endif
                                <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                            @else
                                <form method="POST" action="{{ route('admin.jobs.restore', $job->id) }}" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-success">Restore</button></form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center py-4 text-muted">No jobs found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($jobs->hasPages())
        <div class="card-footer bg-white">{{ $jobs->links() }}</div>
    @endif
</div>
@endsection
