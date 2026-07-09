@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-2">Job Seeker Dashboard</h1>
                <p class="text-muted mb-0">Welcome, {{ $user->name ?? $user->email }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                <a href="{{ route('job-seeker.profile.edit') }}" class="btn btn-primary">Manage Profile</a>
                <a href="{{ route('job-seeker.educations.index') }}" class="btn btn-outline-primary">Manage Education</a>
                <a href="{{ route('job-seeker.experiences.index') }}" class="btn btn-outline-primary">Manage Experience</a>
                <a href="{{ route('job-seeker.skills.index') }}" class="btn btn-outline-primary">Manage Skills</a>
                <a href="{{ route('job-seeker.resumes.index') }}" class="btn btn-outline-primary">Manage Resume</a>
            </div>
        </div>

        @if ($profile)
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0">Profile Overview</h2>
                                <span class="badge bg-success">{{ $completionDetails['percentage'] }}% Complete</span>
                            </div>

                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $completionDetails['percentage'] }}%"></div>
                            </div>

                            <p class="mb-0 text-muted">Completed sections: {{ implode(', ', $completionDetails['completed_sections']) ?: 'None' }}</p>
                            <p class="mb-0 text-muted">Missing sections: {{ implode(', ', $completionDetails['missing_sections']) ?: 'None' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Profile Summary</h2>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Name:</strong> {{ $profile->first_name }} {{ $profile->last_name }}</li>
                                <li><strong>Status:</strong> {{ $profileStatus }}</li>
                                <li><strong>Availability:</strong> {{ $availabilityStatus }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h6">Education</h3>
                            <p class="display-6 mb-0">{{ $educationCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h6">Experience</h3>
                            <p class="display-6 mb-0">{{ $experienceCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h6">Skills</h3>
                            <p class="display-6 mb-0">{{ $skillsCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h6">Resumes</h3>
                            <p class="display-6 mb-0">{{ $resumeCount }}</p>
                            @if ($defaultResume)
                                <small class="text-muted">Default: {{ Str::limit($defaultResume->title, 20) }}</small>
                            @else
                                <small class="text-muted">No default resume</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">Complete Your Profile</h2>
                    <p class="text-muted mb-3">Your job seeker profile has not been created yet. Add your details to unlock the dashboard experience.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('job-seeker.profile.edit') }}" class="btn btn-primary">Complete Your Profile</a>
                        <a href="{{ route('job-seeker.educations.index') }}" class="btn btn-outline-primary">Manage Education</a>
                        <a href="{{ route('job-seeker.experiences.index') }}" class="btn btn-outline-primary">Manage Experience</a>
                        <a href="{{ route('job-seeker.skills.index') }}" class="btn btn-outline-primary">Manage Skills</a>
                        <a href="{{ route('job-seeker.resumes.index') }}" class="btn btn-outline-primary">Manage Resume</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
