@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <h1 class="h3 mb-1">Job Seeker Dashboard</h1>
                    <p class="text-muted mb-0">Welcome, {{ $user->name ?? $user->email }}</p>
                </div>
                @if ($profile && ($profile->profile_photo_url ?? false))
                    <img src="{{ $profile->profile_photo_url }}" alt="Profile Avatar" class="rounded-circle border" width="64" height="64">
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('job-seeker.profile.edit') }}" class="btn btn-primary">Manage Profile</a>
                <a href="{{ route('job-seeker.educations.index') }}" class="btn btn-outline-primary">Manage Education</a>
                <a href="{{ route('job-seeker.experiences.index') }}" class="btn btn-outline-primary">Manage Experience</a>
                <a href="{{ route('job-seeker.skills.index') }}" class="btn btn-outline-primary">Manage Skills</a>
                <a href="{{ route('job-seeker.resumes.index') }}" class="btn btn-outline-primary">Manage Resume</a>
            </div>
        </div>

        @if ($profile)
            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h2 class="h5 mb-1">Profile Overview</h2>
                                    <p class="text-muted mb-0">Keep your profile up to date to stand out to employers.</p>
                                </div>
                                <span class="badge bg-{{ $completionBadgeClass }}">{{ $completionDetails['percentage'] }}% Complete</span>
                            </div>

                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-{{ $completionBadgeClass }}" role="progressbar" style="width: {{ $completionDetails['percentage'] }}%"></div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($completionDetails['completed_sections'] as $section)
                                    <span class="badge bg-success">{{ str_replace('_', ' ', $section) }}</span>
                                @endforeach
                                @foreach ($completionDetails['missing_sections'] as $section)
                                    <span class="badge bg-secondary">{{ str_replace('_', ' ', $section) }}</span>
                                @endforeach
                            </div>

                            <p class="mb-1 text-muted"><strong>Completed sections:</strong> {{ implode(', ', $completionDetails['completed_sections']) ?: 'None' }}</p>
                            <p class="mb-0 text-muted"><strong>Missing sections:</strong> {{ implode(', ', $completionDetails['missing_sections']) ?: 'None' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h6">Profile Status</h2>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Profile Status</span>
                                <span class="badge bg-{{ $profileStatusClass }}">{{ $profileStatus }}</span>
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Name:</strong> {{ $profile->first_name }} {{ $profile->last_name }}</li>
                                <li class="mb-2"><strong>Availability:</strong> {{ $availabilityStatus }}</li>
                                <li class="mb-2"><strong>Current Job Title:</strong> {{ $profile->current_job_title ?: 'Not provided' }}</li>
                                <li class="mb-2"><strong>Current Company:</strong> {{ $profile->current_company ?: 'Not provided' }}</li>
                                <li class="mb-0"><strong>Professional Title:</strong> {{ $profile->professional_title ?: 'Not provided' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <h2 class="h5 mb-3">Statistics</h2>
                <div class="row g-4">
                    @php($stats = [
                        ['icon' => '🎓', 'title' => 'Education', 'count' => $educationCount, 'status' => $educationCount > 0 ? 'Added' : 'Pending'],
                        ['icon' => '💼', 'title' => 'Experience', 'count' => $experienceCount, 'status' => $experienceCount > 0 ? 'Added' : 'Pending'],
                        ['icon' => '🛠️', 'title' => 'Skills', 'count' => $skillsCount, 'status' => $skillsCount > 0 ? 'Added' : 'Pending'],
                        ['icon' => '📄', 'title' => 'Resumes', 'count' => $resumeCount, 'status' => $resumeCount > 0 ? ($defaultResume ? 'Default available' : 'Uploaded') : 'Pending'],
                        ['icon' => '📈', 'title' => 'Profile Completion', 'count' => $completionDetails['percentage'].'%', 'status' => $completionDetails['percentage'] >= 100 ? 'Complete' : 'In progress'],
                    ])
                    @foreach ($stats as $stat)
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-2-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="display-6">{{ $stat['icon'] }}</div>
                                        <span class="badge bg-light text-dark">{{ $stat['status'] }}</span>
                                    </div>
                                    <h3 class="h6 mb-1">{{ $stat['title'] }}</h3>
                                    <p class="display-6 mb-0">{{ $stat['count'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                <h2 class="h5 mb-3">Quick Actions</h2>
                <div class="row g-3">
                    @php($actions = [
                        ['title' => 'Manage Profile', 'description' => 'Update personal and professional details.', 'route' => route('job-seeker.profile.edit'), 'btn' => 'Manage Profile'],
                        ['title' => 'Manage Education', 'description' => 'Add or refine your academic background.', 'route' => route('job-seeker.educations.index'), 'btn' => 'Open Education'],
                        ['title' => 'Manage Experience', 'description' => 'Highlight your professional milestones.', 'route' => route('job-seeker.experiences.index'), 'btn' => 'Open Experience'],
                        ['title' => 'Manage Skills', 'description' => 'Showcase the tools and strengths you use.', 'route' => route('job-seeker.skills.index'), 'btn' => 'Open Skills'],
                        ['title' => 'Manage Resume', 'description' => 'Upload or refresh your resume documents.', 'route' => route('job-seeker.resumes.index'), 'btn' => 'Open Resume'],
                    ])
                    @foreach ($actions as $action)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="h6 mb-2">{{ $action['title'] }}</h3>
                                    <p class="text-muted flex-grow-1">{{ $action['description'] }}</p>
                                    <a href="{{ $action['route'] }}" class="btn btn-outline-primary btn-sm">{{ $action['btn'] }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Recent Activity</h2>
                            @if ($recentActivities->isEmpty())
                                <div class="text-muted">No recent activity yet.</div>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($recentActivities as $activity)
                                        <li class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start gap-3">
                                                <div>
                                                    <div class="fw-semibold">{{ $activity['title'] }}</div>
                                                    <div class="small text-muted">{{ $activity['type'] }} · {{ $activity['description'] }}</div>
                                                </div>
                                                <span class="small text-muted">{{ $activity['created_at']?->diffForHumans() }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Profile Completion</h2>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Completion</span>
                                <span class="fw-semibold">{{ $completionDetails['percentage'] }}%</span>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-{{ $completionBadgeClass }}" role="progressbar" style="width: {{ $completionDetails['percentage'] }}%"></div>
                            </div>
                            <div class="mb-3">
                                <div class="fw-semibold mb-2">Completed Sections</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse ($completionDetails['completed_sections'] as $section)
                                        <span class="badge bg-success">{{ str_replace('_', ' ', $section) }}</span>
                                    @empty
                                        <span class="text-muted">None</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <div class="fw-semibold mb-2">Missing Sections</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse ($completionDetails['missing_sections'] as $section)
                                        <span class="badge bg-secondary">{{ str_replace('_', ' ', $section) }}</span>
                                    @empty
                                        <span class="text-muted">None</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="h5 mb-1">Recommended Jobs</h2>
                        <p class="text-muted mb-0">Jobs we think match your profile best.</p>
                    </div>
                    <a href="{{ route('jobs.index', ['recommended' => 1]) }}" class="btn btn-outline-primary btn-sm">Explore All Recommended</a>
                </div>

                <div class="row g-4 mb-4">
                    @forelse ($recommendedJobs as $recommendation)
                        @php($job = $recommendation['job'])
                        <div class="col-12 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h3 class="h6 mb-1">{{ $job->title }}</h3>
                                            <div class="text-muted small">{{ optional($job->company)->company_name }}</div>
                                        </div>
                                        <span class="badge bg-success">{{ $recommendation['level'] }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <p class="mb-1 text-muted small">{{ $job->location }} · {{ $job->job_type }}</p>
                                        <p class="mb-0"><strong>Score:</strong> {{ $recommendation['score'] }}%</p>
                                    </div>

                                    <p class="text-muted mb-3 small">{{ $recommendation['reason'] }}</p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-primary btn-sm">View Job</a>
                                        <span class="text-muted small">Salary: {{ $job->salary_type }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 shadow-sm p-4 text-center">
                                <div class="mb-3">
                                    <span class="fs-1">🔍</span>
                                </div>
                                <h5 class="card-title">No recommended jobs yet</h5>
                                <p class="text-muted mb-0">Update your profile or add more skills to get tailored suggestions.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                <h2 class="h5 mb-3">Your Highlights</h2>
                <div class="row g-4">
                    <div class="col-12 col-lg-6">
                        @if ($educationCount > 0)
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h3 class="h6">Education</h3>
                                    <p class="text-muted mb-0">You have {{ $educationCount }} education record{{ $educationCount === 1 ? '' : 's' }} ready to view.</p>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-6 mb-2">🎓</div>
                                    <h3 class="h6">No education added yet</h3>
                                    <p class="text-muted mb-3">Add your academic background to strengthen your profile.</p>
                                    <a href="{{ route('job-seeker.educations.index') }}" class="btn btn-outline-primary btn-sm">Add Education</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-lg-6">
                        @if ($experienceCount > 0)
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h3 class="h6">Experience</h3>
                                    <p class="text-muted mb-0">You have {{ $experienceCount }} experience record{{ $experienceCount === 1 ? '' : 's' }} ready to view.</p>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-6 mb-2">💼</div>
                                    <h3 class="h6">No experience added yet</h3>
                                    <p class="text-muted mb-3">Add your work history to show your professional path.</p>
                                    <a href="{{ route('job-seeker.experiences.index') }}" class="btn btn-outline-primary btn-sm">Add Experience</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-lg-6">
                        @if ($skillsCount > 0)
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h3 class="h6">Skills</h3>
                                    <p class="text-muted mb-0">You have {{ $skillsCount }} skill{{ $skillsCount === 1 ? '' : 's' }} highlighted.</p>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-6 mb-2">🛠️</div>
                                    <h3 class="h6">No skills added yet</h3>
                                    <p class="text-muted mb-3">List your strongest areas to improve matching.</p>
                                    <a href="{{ route('job-seeker.skills.index') }}" class="btn btn-outline-primary btn-sm">Add Skills</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-lg-6">
                        @if ($resumeCount > 0)
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h3 class="h6">Resumes</h3>
                                    <p class="text-muted mb-0">You have {{ $resumeCount }} resume{{ $resumeCount === 1 ? '' : 's' }} ready to share.</p>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-6 mb-2">📄</div>
                                    <h3 class="h6">No resumes uploaded yet</h3>
                                    <p class="text-muted mb-3">Upload a polished resume to make applications easier.</p>
                                    <a href="{{ route('job-seeker.resumes.index') }}" class="btn btn-outline-primary btn-sm">Upload Resume</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
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
