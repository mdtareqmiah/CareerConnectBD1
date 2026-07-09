@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-2">Add Education</h1>
                        <p class="text-muted mb-4">Share your academic background with employers.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('job-seeker.educations.store') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-6">
                                <label for="degree" class="form-label">Degree</label>
                                <input type="text" class="form-control @error('degree') is-invalid @enderror" id="degree" name="degree" value="{{ old('degree') }}" required>
                                @error('degree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="field_of_study" class="form-label">Field of Study</label>
                                <input type="text" class="form-control @error('field_of_study') is-invalid @enderror" id="field_of_study" name="field_of_study" value="{{ old('field_of_study') }}" required>
                                @error('field_of_study')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="institution_name" class="form-label">Institution</label>
                                <input type="text" class="form-control @error('institution_name') is-invalid @enderror" id="institution_name" name="institution_name" value="{{ old('institution_name') }}" required>
                                @error('institution_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="board_or_university" class="form-label">Board or University</label>
                                <input type="text" class="form-control @error('board_or_university') is-invalid @enderror" id="board_or_university" name="board_or_university" value="{{ old('board_or_university') }}">
                                @error('board_or_university')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="education_level" class="form-label">Education Level</label>
                                <input type="text" class="form-control @error('education_level') is-invalid @enderror" id="education_level" name="education_level" value="{{ old('education_level') }}">
                                @error('education_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="result" class="form-label">Result / CGPA</label>
                                <input type="text" class="form-control @error('result') is-invalid @enderror" id="result" name="result" value="{{ old('result') }}">
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="grading_system" class="form-label">Grading System</label>
                                <input type="text" class="form-control @error('grading_system') is-invalid @enderror" id="grading_system" name="grading_system" value="{{ old('grading_system') }}">
                                @error('grading_system')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="passing_year" class="form-label">Passing Year</label>
                                <input type="number" min="1900" max="2100" class="form-control @error('passing_year') is-invalid @enderror" id="passing_year" name="passing_year" value="{{ old('passing_year') }}">
                                @error('passing_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_current" name="is_current" {{ old('is_current') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_current">Currently Studying</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <a href="{{ route('job-seeker.educations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Education</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
