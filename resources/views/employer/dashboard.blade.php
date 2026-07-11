@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="mb-5">
        <h1 class="h2 mb-2">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-muted">Manage your employer account and recruit top talent.</p>
    </div>

    @if (!$company)
        <!-- Company Missing Alert -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Company Profile Not Set Up</h5>
            <p class="mb-2">To start recruiting, please create your company profile with all essential information.</p>
            <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm">Create Company Profile</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Job Postings</p>
                            <h3 class="mb-0">{{ $stats['job_postings'] }}</h3>
                        </div>
                        <div class="badge bg-primary p-2">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Applications Received</p>
                            <h3 class="mb-0">{{ $stats['total_applications'] }}</h3>
                        </div>
                        <div class="badge bg-success p-2">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Profile Completion</p>
                            <h3 class="mb-0">{{ $stats['profile_completion'] }}%</h3>
                        </div>
                        <div class="badge bg-info p-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            @if ($company)
                                <a href="{{ route('company.edit', $company) }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-edit"></i> Manage Company Profile
                                </a>
                            @else
                                <a href="{{ route('company.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-building"></i> Create Company Profile
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('jobs.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-briefcase"></i> Post a Job
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-briefcase"></i> Manage Jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Overview -->
    @if ($company)
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Your Company</h6>
                        <a href="{{ route('company.show', $company) }}" class="btn btn-sm btn-link">View Full Profile</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <img src="{{ $company->company_logo_url }}" alt="{{ $company->company_name }}" class="img-fluid rounded" style="max-width: 150px; max-height: 150px;">
                            </div>
                            <div class="col-md-9">
                                <h5>{{ $company->company_name }}</h5>
                                <p class="text-muted mb-3">{{ $company->company_description ?? 'No description provided.' }}</p>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p class="small"><strong>Industry:</strong> {{ $company->industry }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="small"><strong>Company Size:</strong> {{ $company->company_size }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="small"><strong>Email:</strong> <a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="small"><strong>Phone:</strong> <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="small"><strong>Location:</strong> {{ $company->city }}, {{ $company->country }}</p>
                                    </div>
                                    @if ($company->website)
                                        <div class="col-md-6 mb-2">
                                            <p class="small"><strong>Website:</strong> <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></p>
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
