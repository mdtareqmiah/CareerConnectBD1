@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $company->company_name }}</li>
                </ol>
            </nav>

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                        <i class="bi bi-building"></i>
                        <span class="fw-semibold">Company profile</span>
                    </div>
                    <h1 class="h3 mb-2">{{ $company->company_name }}</h1>
                    <p class="text-muted mb-0">{{ $company->industry }} • {{ $company->company_size }} employees</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('company.edit', $company) }}" class="btn btn-primary">Edit profile</a>
                    <form action="{{ route('company.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company profile?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-3">
                    <div class="card border-0 shadow-soft text-center h-100">
                        <div class="card-body p-4">
                            <img src="{{ $company->company_logo_url }}" alt="{{ $company->company_name }}" class="img-fluid rounded-4 mb-3" style="max-width: 150px; max-height: 150px;">
                            <h5 class="card-title">{{ $company->company_name }}</h5>
                            <p class="text-muted small mb-0">{{ $company->industry }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="card border-0 shadow-soft h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h6 class="mb-0 fw-semibold">Contact information</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Email</p>
                                    <p class="mb-0"><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Phone</p>
                                    <p class="mb-0"><a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Website</p>
                                    <p class="mb-0">
                                        @if ($company->website)
                                            <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Founded year</p>
                                    <p class="mb-0">{{ $company->founded_year }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-soft h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h6 class="mb-0 fw-semibold">Company details</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <p class="text-muted mb-1">Industry</p>
                                <p class="mb-0">{{ $company->industry }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Company size</p>
                                <p class="mb-0">{{ $company->company_size }} employees</p>
                            </div>
                            <div class="mb-0">
                                <p class="text-muted mb-1">Founded year</p>
                                <p class="mb-0">{{ $company->founded_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-soft h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h6 class="mb-0 fw-semibold">Location</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <p class="text-muted mb-1">Address</p>
                                <p class="mb-0">{{ $company->address }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">City</p>
                                <p class="mb-0">{{ $company->city }}</p>
                            </div>
                            <div class="mb-0">
                                <p class="text-muted mb-1">Country</p>
                                <p class="mb-0">{{ $company->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($company->company_description)
                <div class="card border-0 shadow-soft mt-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold">About company</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0">{{ $company->company_description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
