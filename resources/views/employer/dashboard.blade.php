@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-buildings"></i>
                <span class="fw-semibold">Employer workspace</span>
            </div>
            <h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-muted mb-0">Manage your employer account and recruit top talent.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($company)
                <a href="{{ route('company.edit', $company) }}" class="btn btn-outline-primary">Edit company</a>
            @else
                <a href="{{ route('company.create') }}" class="btn btn-primary">Create company</a>
            @endif
            <a href="{{ route('jobs.create') }}" class="btn btn-outline-primary">Post a job</a>
        </div>
    </div>

    @if (!$company)
        <div class="alert alert-warning rounded-4 border-0 shadow-sm d-flex flex-column flex-md-row align-items-md-start gap-3 mb-4" role="alert">
            <div class="rounded-circle bg-white p-2"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <h5 class="alert-heading mb-2">Company profile not set up</h5>
                <p class="mb-2">To start recruiting, please create your company profile with the essential information.</p>
                <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm">Create company profile</a>
            </div>
        </div>
    @endif

    <div class="row mb-4 g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-soft h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Job postings</p>
                            <h3 class="mb-0">{{ $stats['job_postings'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary-subtle text-primary p-3">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-soft h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Applications received</p>
                            <h3 class="mb-0">{{ $applicationStats['total_applications'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-success-subtle text-success p-3">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                    </div>

                    <div class="mt-3 row g-2 text-center">
                        <div class="col-6">
                            <span class="badge bg-light text-dark">Pending</span>
                            <div class="fw-semibold mt-1">{{ $applicationStats['pending_applications'] }}</div>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-light text-dark">Reviewed</span>
                            <div class="fw-semibold mt-1">{{ $applicationStats['reviewed_applications'] }}</div>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-light text-dark">Shortlisted</span>
                            <div class="fw-semibold mt-1">{{ $applicationStats['shortlisted_applications'] }}</div>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-light text-dark">Rejected</span>
                            <div class="fw-semibold mt-1">{{ $applicationStats['rejected_applications'] }}</div>
                        </div>
                        <div class="col-12">
                            <span class="badge bg-light text-dark">Hired</span>
                            <div class="fw-semibold mt-1">{{ $applicationStats['hired_applications'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-soft h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Profile completion</p>
                            <h3 class="mb-0">{{ $stats['profile_completion'] }}%</h3>
                        </div>
                        <div class="rounded-circle bg-info-subtle text-info p-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card border-0 shadow-soft">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold">Quick actions</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            @if ($company)
                                <a href="{{ route('company.edit', $company) }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-pencil-square me-2"></i> Manage company profile
                                </a>
                            @else
                                <a href="{{ route('company.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-building me-2"></i> Create company profile
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('jobs.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i> Post a job
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-list-ul me-2"></i> Manage jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($company)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-soft">
                    <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">Your company</h6>
                        <a href="{{ route('company.show', $company) }}" class="btn btn-sm btn-outline-primary">View full profile</a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-3 text-center">
                                <img src="{{ $company->company_logo_url }}" alt="{{ $company->company_name }}" class="img-fluid rounded-4" style="max-width: 150px; max-height: 150px;">
                            </div>
                            <div class="col-lg-9">
                                <h5>{{ $company->company_name }}</h5>
                                <p class="text-muted mb-3">{{ $company->company_description ?? 'No description provided.' }}</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p class="small mb-1"><strong>Industry:</strong> {{ $company->industry }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small mb-1"><strong>Company size:</strong> {{ $company->company_size }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small mb-1"><strong>Email:</strong> <a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small mb-1"><strong>Phone:</strong> <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small mb-1"><strong>Location:</strong> {{ $company->city }}, {{ $company->country }}</p>
                                    </div>
                                    @if ($company->website)
                                        <div class="col-md-6">
                                            <p class="small mb-1"><strong>Website:</strong> <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
