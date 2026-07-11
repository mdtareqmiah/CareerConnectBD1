@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h4 mb-1">{{ $job->title }}</h1>
                        <p class="text-muted mb-1">{{ $job->location }} · {{ $job->job_type }} · {{ ucfirst($job->status) }}</p>
                        <p class="mb-0">Deadline: {{ $job->deadline->format('F j, Y') }}</p>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this job?')">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Vacancy:</strong> {{ $job->vacancy }}
                        </div>
                        <div class="mb-3">
                            <strong>Job Type:</strong> {{ $job->job_type }}
                        </div>
                        <div class="mb-3">
                            <strong>Workplace:</strong> {{ $job->workplace }}
                        </div>
                        <div class="mb-3">
                            <strong>Experience Level:</strong> {{ $job->experience_level }}
                        </div>
                        <div class="mb-3">
                            <strong>Education Level:</strong> {{ $job->education_level }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Salary:</strong> {{ $job->salary_type }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</div>
                        <div class="mb-3">
                            <strong>Company:</strong> {{ $job->company->company_name }}</div>
                        <div class="mb-3">
                            <strong>Published At:</strong> {{ $job->published_at?->format('F j, Y h:i A') ?? 'Not published' }}</div>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="mb-0">{{ $job->description }}</p>
                </div>

                <div class="mb-4">
                    <h5>Responsibilities</h5>
                    <p class="mb-0">{{ $job->responsibilities }}</p>
                </div>

                <div class="mb-4">
                    <h5>Requirements</h5>
                    <p class="mb-0">{{ $job->requirements }}</p>
                </div>

                <div class="mb-4">
                    <h5>Benefits</h5>
                    <p class="mb-0">{{ $job->benefits ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
