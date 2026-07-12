@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                        <i class="bi bi-send-check"></i>
                        <span class="fw-semibold">Application</span>
                    </div>
                    <h2 class="h3 mb-1">Apply for {{ $job->title }}</h2>
                    <p class="text-muted mb-0">{{ optional($job->company)->company_name ?? 'Company' }}</p>
                </div>
                <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary">Back to job</a>
            </div>

            <div class="card border-0 shadow-soft">
                <div class="card-body p-4 p-lg-5">
                    @if ($resumes->isEmpty())
                        <div class="alert alert-warning rounded-4 border-0 shadow-sm">
                            You do not have any active resumes. Please add a resume before applying.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('job-applications.store') }}">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div class="mb-4">
                            <label for="resume_id" class="form-label">Resume</label>
                            <select id="resume_id" name="resume_id" class="form-select @error('resume_id') is-invalid @enderror" required>
                                <option value="">Select your resume</option>
                                @foreach ($resumes as $resume)
                                    <option value="{{ $resume->id }}"
                                        @if(old('resume_id') == $resume->id || (old('resume_id') === null && $resume->is_default)) selected @endif>
                                        {{ $resume->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('resume_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="cover_letter" class="form-label">Cover letter</label>
                            <textarea id="cover_letter" name="cover_letter" rows="8" class="form-control @error('cover_letter') is-invalid @enderror" required>{{ old('cover_letter') }}</textarea>
                            <div class="form-text">Minimum 50 characters; maximum 3000 characters.</div>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <span class="text-muted">Status: <strong>{{ ucfirst($job->status) }}</strong></span>
                            <button type="submit" class="btn btn-primary" @if($resumes->isEmpty()) disabled @endif>Submit application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
