@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="h4 mb-1">Apply for {{ $job->title }}</h2>
                        <p class="text-muted mb-0">{{ optional($job->company)->company_name ?? 'Company' }}</p>
                    </div>
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-link">Back to Job</a>
                </div>

                @if ($resumes->isEmpty())
                    <div class="alert alert-warning">
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
                        <label for="cover_letter" class="form-label">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter" rows="8" class="form-control @error('cover_letter') is-invalid @enderror" required>{{ old('cover_letter') }}</textarea>
                        <div class="form-text">Minimum 50 characters; maximum 3000 characters.</div>
                        @error('cover_letter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status: <strong>{{ ucfirst($job->status) }}</strong></span>
                        <button type="submit" class="btn btn-primary" @if($resumes->isEmpty()) disabled @endif>Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
