@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h1 class="h3 mb-2">Edit Your Profile</h1>
                                <p class="text-muted mb-0">Keep your professional details up to date.</p>
                            </div>
                            <a href="{{ route('job-seeker.dashboard') }}" class="btn btn-outline-secondary mt-3 mt-md-0">Back to Dashboard</a>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('job-seeker.profile.update') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PATCH')

                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $profile->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $profile->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <input type="text" class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" value="{{ old('gender', $profile->gender) }}">
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $profile->nationality) }}">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $profile->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $profile->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $profile->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $profile->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="professional_title" class="form-label">Professional Title</label>
                                <input type="text" class="form-control @error('professional_title') is-invalid @enderror" id="professional_title" name="professional_title" value="{{ old('professional_title', $profile->professional_title) }}">
                                @error('professional_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="professional_summary" class="form-label">Professional Summary</label>
                                <textarea class="form-control @error('professional_summary') is-invalid @enderror" id="professional_summary" name="professional_summary" rows="4">{{ old('professional_summary', $profile->professional_summary) }}</textarea>
                                @error('professional_summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="current_job_title" class="form-label">Current Job Title</label>
                                <input type="text" class="form-control @error('current_job_title') is-invalid @enderror" id="current_job_title" name="current_job_title" value="{{ old('current_job_title', $profile->current_job_title) }}">
                                @error('current_job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="current_company" class="form-label">Current Company</label>
                                <input type="text" class="form-control @error('current_company') is-invalid @enderror" id="current_company" name="current_company" value="{{ old('current_company', $profile->current_company) }}">
                                @error('current_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="years_of_experience" class="form-label">Years of Experience</label>
                                <input type="number" min="0" max="60" class="form-control @error('years_of_experience') is-invalid @enderror" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $profile->years_of_experience) }}">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="expected_salary" class="form-label">Expected Salary</label>
                                <input type="number" step="0.01" class="form-control @error('expected_salary') is-invalid @enderror" id="expected_salary" name="expected_salary" value="{{ old('expected_salary', $profile->expected_salary) }}">
                                @error('expected_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="preferred_job_type" class="form-label">Preferred Job Type</label>
                                <input type="text" class="form-control @error('preferred_job_type') is-invalid @enderror" id="preferred_job_type" name="preferred_job_type" value="{{ old('preferred_job_type', $profile->preferred_job_type) }}">
                                @error('preferred_job_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="preferred_workplace" class="form-label">Preferred Workplace</label>
                                <input type="text" class="form-control @error('preferred_workplace') is-invalid @enderror" id="preferred_workplace" name="preferred_workplace" value="{{ old('preferred_workplace', $profile->preferred_workplace) }}">
                                @error('preferred_workplace')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="preferred_location" class="form-label">Preferred Location</label>
                                <input type="text" class="form-control @error('preferred_location') is-invalid @enderror" id="preferred_location" name="preferred_location" value="{{ old('preferred_location', $profile->preferred_location) }}">
                                @error('preferred_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url) }}">
                                @error('linkedin_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="github_url" class="form-label">GitHub URL</label>
                                <input type="url" class="form-control @error('github_url') is-invalid @enderror" id="github_url" name="github_url" value="{{ old('github_url', $profile->github_url) }}">
                                @error('github_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="portfolio_url" class="form-label">Portfolio URL</label>
                                <input type="url" class="form-control @error('portfolio_url') is-invalid @enderror" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url) }}">
                                @error('portfolio_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="website_url" class="form-label">Website URL</label>
                                <input type="url" class="form-control @error('website_url') is-invalid @enderror" id="website_url" name="website_url" value="{{ old('website_url', $profile->website_url) }}">
                                @error('website_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_available_for_work" name="is_available_for_work" {{ old('is_available_for_work', $profile->is_available_for_work) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available_for_work">Available For Work</label>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <a href="{{ route('job-seeker.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
