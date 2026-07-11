@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Edit Job</h1>

                <form method="POST" action="{{ route('jobs.update', $job) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Job Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $job->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="vacancy" class="form-label">Vacancy</label>
                            <input id="vacancy" type="number" name="vacancy" value="{{ old('vacancy', $job->vacancy) }}" class="form-control @error('vacancy') is-invalid @enderror" min="1" required>
                            @error('vacancy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input id="location" type="text" name="location" value="{{ old('location', $job->location) }}" class="form-control @error('location') is-invalid @enderror" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="job_type" class="form-label">Job Type</label>
                            <input id="job_type" type="text" name="job_type" value="{{ old('job_type', $job->job_type) }}" class="form-control @error('job_type') is-invalid @enderror" required>
                            @error('job_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="workplace" class="form-label">Workplace</label>
                            <input id="workplace" type="text" name="workplace" value="{{ old('workplace', $job->workplace) }}" class="form-control @error('workplace') is-invalid @enderror" required>
                            @error('workplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="employment_status" class="form-label">Employment Status</label>
                            <input id="employment_status" type="text" name="employment_status" value="{{ old('employment_status', $job->employment_status) }}" class="form-control @error('employment_status') is-invalid @enderror" required>
                            @error('employment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="experience_level" class="form-label">Experience Level</label>
                            <input id="experience_level" type="text" name="experience_level" value="{{ old('experience_level', $job->experience_level) }}" class="form-control @error('experience_level') is-invalid @enderror" required>
                            @error('experience_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Education Level</label>
                            <input id="education_level" type="text" name="education_level" value="{{ old('education_level', $job->education_level) }}" class="form-control @error('education_level') is-invalid @enderror" required>
                            @error('education_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="salary_type" class="form-label">Salary Type</label>
                            <input id="salary_type" type="text" name="salary_type" value="{{ old('salary_type', $job->salary_type) }}" class="form-control @error('salary_type') is-invalid @enderror" required>
                            @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="salary_min" class="form-label">Salary Min</label>
                            <input id="salary_min" type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="form-control @error('salary_min') is-invalid @enderror" min="0" required>
                            @error('salary_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="salary_max" class="form-label">Salary Max</label>
                            <input id="salary_max" type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="form-control @error('salary_max') is-invalid @enderror" min="0" required>
                            @error('salary_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deadline" class="form-label">Application Deadline</label>
                        <input id="deadline" type="date" name="deadline" value="{{ old('deadline', $job->deadline?->format('Y-m-d')) }}" class="form-control @error('deadline') is-invalid @enderror" required>
                        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $job->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="responsibilities" class="form-label">Responsibilities</label>
                        <textarea id="responsibilities" name="responsibilities" rows="3" class="form-control @error('responsibilities') is-invalid @enderror" required>{{ old('responsibilities', $job->responsibilities) }}</textarea>
                        @error('responsibilities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea id="requirements" name="requirements" rows="3" class="form-control @error('requirements') is-invalid @enderror" required>{{ old('requirements', $job->requirements) }}</textarea>
                        @error('requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="benefits" class="form-label">Benefits</label>
                        <textarea id="benefits" name="benefits" rows="2" class="form-control @error('benefits') is-invalid @enderror">{{ old('benefits', $job->benefits) }}</textarea>
                        @error('benefits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $job->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $job->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $job->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
