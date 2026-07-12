@props(['action', 'method' => 'POST', 'experience' => null, 'submitLabel' => 'Save Experience'])

<form action="{{ $action }}" method="POST" class="row g-4">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-6">
        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control rounded-3 @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $experience?->company_name) }}" required>
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control rounded-3 @error('job_title') is-invalid @enderror" id="job_title" name="job_title" value="{{ old('job_title', $experience?->job_title) }}" required>
        @error('job_title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
        <input type="text" class="form-control rounded-3 @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type" value="{{ old('employment_type', $experience?->employment_type) }}" required>
        @error('employment_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control rounded-3 @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $experience?->location) }}">
        @error('location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control rounded-3 @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', optional($experience?->start_date)->format('Y-m-d')) }}" required>
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control rounded-3 @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', optional($experience?->end_date)->format('Y-m-d')) }}">
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="currently_working" name="currently_working" {{ old('currently_working', $experience?->currently_working) ? 'checked' : '' }}>
            <label class="form-check-label" for="currently_working">Currently Working</label>
        </div>
    </div>

    <div class="col-12">
        <label for="job_description" class="form-label">Job Description</label>
        <textarea class="form-control rounded-3 @error('job_description') is-invalid @enderror" id="job_description" name="job_description" rows="4">{{ old('job_description', $experience?->job_description) }}</textarea>
        @error('job_description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('job-seeker.experiences.index') }}" class="btn btn-outline-secondary rounded-pill">Cancel</a>
        <button type="submit" class="btn btn-primary rounded-pill">{{ $submitLabel }}</button>
    </div>
</form>
