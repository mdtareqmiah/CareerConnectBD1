@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Company Profile</li>
                </ol>
            </nav>

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                        <i class="bi bi-building-add"></i>
                        <span class="fw-semibold">Company onboarding</span>
                    </div>
                    <h1 class="h3 mb-2">Create company profile</h1>
                    <p class="text-muted mb-0">Set up your company information to get started with recruiting.</p>
                </div>
            </div>

            <div class="card border-0 shadow-soft">
                <div class="card-body p-4 p-lg-5">
                    <div class="border rounded-4 bg-light p-3 p-md-4 mb-4">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                            <div>
                                <p class="fw-semibold mb-1">Tell your story clearly from the start.</p>
                                <p class="text-muted small mb-0">A polished profile helps candidates understand your team, brand, and hiring focus.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="company_logo" class="form-label">Company logo</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml">
                            </div>
                            <small class="form-text text-muted d-block mt-2">PNG, JPEG, SVG (Max 2MB)</small>
                            @error('company_logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="industry" class="form-label">Industry <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('industry') is-invalid @enderror" id="industry" name="industry" value="{{ old('industry') }}" required>
                                @error('industry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="company_size" class="form-label">Company size <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_size') is-invalid @enderror" id="company_size" name="company_size" required>
                                    <option value="">Select size</option>
                                    <option value="1-50" {{ old('company_size') === '1-50' ? 'selected' : '' }}>1-50 employees</option>
                                    <option value="51-200" {{ old('company_size') === '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                    <option value="201-500" {{ old('company_size') === '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                    <option value="501-1000" {{ old('company_size') === '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                                    <option value="1000+" {{ old('company_size') === '1000+' ? 'selected' : '' }}>1000+ employees</option>
                                </select>
                                @error('company_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="founded_year" class="form-label">Founded year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('founded_year') is-invalid @enderror" id="founded_year" name="founded_year" value="{{ old('founded_year') }}" min="1800" max="{{ date('Y') }}" required>
                                @error('founded_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website') }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="company_description" class="form-label">Company description</label>
                            <textarea class="form-control @error('company_description') is-invalid @enderror" id="company_description" name="company_description" rows="4">{{ old('company_description') }}</textarea>
                            @error('company_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Create company profile</button>
                            <a href="{{ route('employer.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
