@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Company Details</h1>
        <div class="text-muted">{{ $company->company_name }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-outline-secondary">Edit Company</a>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-primary">Back</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $company->company_logo_url }}" alt="Logo" width="72" height="72" class="rounded border object-fit-cover">
                    <div>
                        <h2 class="h5 mb-1">{{ $company->company_name }}</h2>
                        <div class="text-muted">{{ $company->industry }} | {{ $company->company_size }}</div>
                    </div>
                </div>

                @if(!empty($company->company_banner))
                    <img src="{{ $company->company_banner }}" alt="Company Banner" class="img-fluid rounded border mb-3">
                @endif

                <p class="mb-0">{{ $company->company_description ?: 'No description provided.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Overview</h3>
                <div class="small text-muted mb-1">Website</div>
                <div class="mb-2">
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                    @else
                        N/A
                    @endif
                </div>
                <div class="small text-muted mb-1">Location</div>
                <div class="mb-2">{{ $company->address }}, {{ $company->city }}, {{ $company->country }}</div>
                <div class="small text-muted mb-1">Employer</div>
                <div class="mb-2">
                    @if($company->employer)
                        <a href="{{ route('admin.employers.show', $company->employer) }}">{{ $company->employer->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
                <div class="small text-muted mb-1">Created Jobs</div>
                <div class="mb-2">{{ $jobsCount }}</div>
                <div class="small text-muted mb-1">Applications Count</div>
                <div class="mb-2">{{ $applicationsCount }}</div>
                <div class="small text-muted mb-1">Verification</div>
                <div class="mb-2">{{ ucfirst($company->verification_status ?? 'pending') }}</div>
                <div class="small text-muted mb-1">Status</div>
                <div>{{ $company->is_active ? 'Active' : 'Inactive' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center" id="jobs">
        <h3 class="h6 mb-0">Recent Jobs</h3>
        <span class="text-muted small">Latest 10</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Title</th><th>Status</th><th>Deadline</th><th>Created</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($company->jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td><span class="badge bg-{{ $job->status_badge_color }}">{{ ucfirst($job->display_status) }}</span></td>
                    <td>{{ $job->deadline?->format('M d, Y') ?? 'N/A' }}</td>
                    <td>{{ $job->created_at?->format('M d, Y') }}</td>
                    <td class="text-end"><a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No jobs found for this company.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
