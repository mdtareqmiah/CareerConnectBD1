@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.applications.index') }}">My Applications</a></li>
            <li class="breadcrumb-item active" aria-current="page">Application Detail</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-file-earmark-check"></i>
                <span class="fw-semibold">Application detail</span>
            </div>
            <h1 class="h3 mb-1">Application detail</h1>
            <p class="text-muted mb-0">Review the application and track next steps for this job.</p>
        </div>
        <a href="{{ route('job-seeker.applications.index') }}" class="btn btn-outline-secondary">Back to applications</a>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                        <div>
                            <h2 class="h5 mb-1">Job information</h2>
                            <p class="text-muted mb-0">A snapshot of the role and the current status of your application.</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $jobApplication->status_label }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold">{{ $jobApplication->job->title }}</div>
                        <div class="text-muted">{{ $jobApplication->job->company->company_name }}</div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="text-muted small">Company</div>
                                <div class="fw-semibold">{{ $jobApplication->job->company->company_name }}</div>
                                <div>{{ $jobApplication->job->company->industry }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="text-muted small">Applied</div>
                                <div class="fw-semibold">{{ $jobApplication->applied_at->format('M d, Y') }}</div>
                                <div class="text-muted small">Status</div>
                                <div>{!! $jobApplication->statusBadge() !!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-soft mb-4">
                        <div class="card-body p-4">
                            <h3 class="h6 mb-3">Cover Letter</h3>
                            <p class="mb-0">{{ $jobApplication->cover_letter ?: 'No cover letter provided.' }}</p>
                        </div>
                    </div>
                    <div class="card border-0 shadow-soft mb-4">
                        <div class="card-body p-4">
                            <h3 class="h6 mb-3">Application Timeline</h3>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="badge bg-primary rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div>
                                        <div class="fw-semibold">Applied</div>
                                        <div class="text-muted">{{ $jobApplication->applied_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="badge bg-{{ $jobApplication->status_badge_color }} rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div>
                                        <div class="fw-semibold">Current Status</div>
                                        <div class="text-muted">{{ $jobApplication->status_label }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Resume used</h2>
                    <div class="mb-3">
                        <div class="fw-semibold">{{ $jobApplication->resume->file_name ?? basename($jobApplication->resume->file_path ?? '') }}</div>
                        <div class="text-muted small">Uploaded {{ $jobApplication->resume->uploaded_at?->format('M d, Y') ?? 'Unknown' }}</div>
                    </div>
                    @if($jobApplication->resume && $jobApplication->resume->file_path)
                        <a href="{{ route('job-seeker.resumes.download', $jobApplication->resume) }}" class="btn btn-outline-primary w-100">Download Resume</a>
                    @endif
                </div>
            </div>
            <div class="card border-0 shadow-soft">
                <div class="card-body p-4">
                    <h2 class="h6 mb-3">Company details</h2>
                    <div class="mb-2"><strong>Name:</strong> {{ $jobApplication->job->company->company_name }}</div>
                    <div class="mb-2"><strong>Industry:</strong> {{ $jobApplication->job->company->industry }}</div>
                    <div class="mb-0"><strong>Location:</strong> {{ $jobApplication->job->company->city }}, {{ $jobApplication->job->company->country }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
