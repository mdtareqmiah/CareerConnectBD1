@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="h4 mb-3">Create Job</h1>
                    <p class="text-muted mb-0">Publish a role that attracts the right candidates.</p>
                </div>
            </div>

            <div class="card border-0 shadow-soft">
                <div class="card-body p-4 p-lg-5">
                    <div class="border rounded-4 bg-light p-3 p-md-4 mb-4">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                            <div>
                                <p class="fw-semibold mb-1">Create a role that stands out.</p>
                                <p class="text-muted small mb-0">Clear job details help candidates quickly understand the role, expectations, and next steps.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('employer.jobs.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Job title</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="vacancy" class="form-label">Vacancy</label>
                                <input id="vacancy" type="number" name="vacancy" value="{{ old('vacancy') }}" class="form-control @error('vacancy') is-invalid @enderror" min="1" required>
                                @error('vacancy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Location</label>
                                <input id="location" type="text" name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" required>
                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="job_type" class="form-label">Job type</label>
                                <input id="job_type" type="text" name="job_type" value="{{ old('job_type') }}" class="form-control @error('job_type') is-invalid @enderror" required>
                                @error('job_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="workplace" class="form-label">Workplace</label>
                                <input id="workplace" type="text" name="workplace" value="{{ old('workplace') }}" class="form-control @error('workplace') is-invalid @enderror" required>
                                @error('workplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="employment_status" class="form-label">Employment status</label>
                                <input id="employment_status" type="text" name="employment_status" value="{{ old('employment_status') }}" class="form-control @error('employment_status') is-invalid @enderror" required>
                                @error('employment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="experience_level" class="form-label">Experience level</label>
                                <input id="experience_level" type="text" name="experience_level" value="{{ old('experience_level') }}" class="form-control @error('experience_level') is-invalid @enderror" required>
                                @error('experience_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="education_level" class="form-label">Education level</label>
                                <input id="education_level" type="text" name="education_level" value="{{ old('education_level') }}" class="form-control @error('education_level') is-invalid @enderror" required>
                                @error('education_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="salary_type" class="form-label">Salary type</label>
                                <input id="salary_type" type="text" name="salary_type" value="{{ old('salary_type') }}" class="form-control @error('salary_type') is-invalid @enderror" required>
                                @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="salary_min" class="form-label">Salary min</label>
                                <input id="salary_min" type="number" name="salary_min" value="{{ old('salary_min') }}" class="form-control @error('salary_min') is-invalid @enderror" min="0" required>
                                @error('salary_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="salary_max" class="form-label">Salary max</label>
                                <input id="salary_max" type="number" name="salary_max" value="{{ old('salary_max') }}" class="form-control @error('salary_max') is-invalid @enderror" min="0" required>
                                @error('salary_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Application deadline</label>
                            <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}" class="form-control @error('deadline') is-invalid @enderror" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="responsibilities" class="form-label">Responsibilities</label>
                            <textarea id="responsibilities" name="responsibilities" rows="3" class="form-control @error('responsibilities') is-invalid @enderror" required>{{ old('responsibilities') }}</textarea>
                            @error('responsibilities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea id="requirements" name="requirements" rows="3" class="form-control @error('requirements') is-invalid @enderror" required>{{ old('requirements') }}</textarea>
                            @error('requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="benefits" class="form-label">Benefits</label>
                            <textarea id="benefits" name="benefits" rows="2" class="form-control @error('benefits') is-invalid @enderror">{{ old('benefits') }}</textarea>
                            @error('benefits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('employer.jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
