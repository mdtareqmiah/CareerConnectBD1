@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $company->company_name }}</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h2 mb-2">{{ $company->company_name }}</h1>
                    <p class="text-muted">{{ $company->industry }} • {{ $company->company_size }} employees</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('company.edit', $company) }}" class="btn btn-primary">Edit Profile</a>
                    <form action="{{ route('company.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company profile?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>

            <!-- Logo & Basic Info -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <img src="{{ $company->company_logo_url }}" alt="{{ $company->company_name }}" class="img-fluid rounded mb-3" style="max-width: 150px; max-height: 150px;">
                            <h5 class="card-title">{{ $company->company_name }}</h5>
                            <p class="text-muted small">{{ $company->industry }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Contact Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Email</p>
                                    <p><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Phone</p>
                                    <p><a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Website</p>
                                    <p>
                                        @if ($company->website)
                                            <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Founded Year</p>
                                    <p>{{ $company->founded_year }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">Company Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted mb-1">Industry</p>
                                <p>{{ $company->industry }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Company Size</p>
                                <p>{{ $company->company_size }} employees</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Founded Year</p>
                                <p>{{ $company->founded_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">Location</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted mb-1">Address</p>
                                <p>{{ $company->address }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">City</p>
                                <p>{{ $company->city }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted mb-1">Country</p>
                                <p>{{ $company->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if ($company->company_description)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0">About Company</h6>
                    </div>
                    <div class="card-body">
                        <p>{{ $company->company_description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
