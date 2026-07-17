@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Application Details</h1>
        <div class="text-muted">{{ $application->user?->name ?? 'Candidate' }} for {{ $application->job?->title ?? 'N/A' }}</div>
    </div>
    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-primary">Back</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Candidate Profile</h3>
                <div class="small text-muted">Name</div>
                <div class="mb-2">{{ $application->user?->name ?? 'N/A' }}</div>
                <div class="small text-muted">Email</div>
                <div class="mb-2">{{ $application->user?->email ?? 'N/A' }}</div>
                <div class="small text-muted">Professional Title</div>
                <div class="mb-2">{{ $application->user?->jobSeekerProfile?->professional_title ?? 'N/A' }}</div>
                <div class="small text-muted">Summary</div>
                <div>{{ $application->user?->jobSeekerProfile?->professional_summary ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Employer / Company</h3>
                <div class="small text-muted">Employer</div>
                <div class="mb-2">
                    @if($application->job?->company?->employer)
                        <a href="{{ route('admin.employers.show', $application->job->company->employer) }}">{{ $application->job->company->employer->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
                <div class="small text-muted">Company</div>
                <div class="mb-2">
                    @if($application->job?->company)
                        <a href="{{ route('admin.companies.show', $application->job->company) }}">{{ $application->job->company->company_name }}</a>
                    @else
                        N/A
                    @endif
                </div>
                <div class="small text-muted">Job</div>
                <div>
                    @if($application->job)
                        <a href="{{ route('admin.jobs.show', $application->job->id) }}">{{ $application->job->title }}</a>
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Application</h3>
                <div class="small text-muted">Status</div>
                <div class="mb-2"><span class="badge bg-{{ $application->status_badge_color }}">{{ $application->status_label }}</span></div>
                <div class="small text-muted">Applied Date</div>
                <div class="mb-2">{{ $application->applied_at?->format('M d, Y h:i A') ?? $application->created_at?->format('M d, Y h:i A') }}</div>
                <div class="small text-muted">Cover Letter</div>
                <div class="mb-3">{{ $application->cover_letter ?: 'N/A' }}</div>
                @if($application->resume)
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.applications.resume.preview', $application) }}" class="btn btn-sm btn-outline-primary">Preview Resume</a>
                        <a href="{{ route('admin.applications.resume.download', $application) }}" class="btn btn-sm btn-outline-secondary">Download Resume</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
