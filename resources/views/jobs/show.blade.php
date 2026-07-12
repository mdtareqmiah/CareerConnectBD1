@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent ps-0 mb-2">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $job->title }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-file-earmark-text"></i>
                <span class="fw-semibold">Job detail</span>
            </div>
            <h1 class="h3 mb-1">{{ $job->title }}</h1>
            <p class="text-muted mb-2">{{ optional($job->company)->company_name ?? 'Company' }}</p>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-{{ $job->status_badge_color }} text-uppercase">{{ $job->display_status }}</span>
                <span class="text-muted">{{ $job->location }}</span>
                <span class="text-muted">&middot;</span>
                <span class="text-muted">{{ $job->job_type }}</span>
                <span class="text-muted">&middot;</span>
                <span class="text-muted">{{ $job->employment_status }}</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Back to jobs</a>
            @php
                $user = auth()->user();
                $alreadyApplied = $user && $user->role?->slug === 'job-seeker' && $job->alreadyAppliedBy($user);
                $canApply = $job->isOpen() && (! $user || $user->role?->slug === 'job-seeker');
            @endphp

            @if ($alreadyApplied)
                <button type="button" class="btn btn-secondary" disabled>Already applied</button>
            @elseif ($canApply)
                <a href="{{ route('jobs.apply', $job) }}" class="btn btn-primary">Apply now</a>
            @endif

            <button type="button" class="btn btn-outline-primary" disabled>Save job</button>
            <button type="button" class="btn btn-outline-secondary">Share</button>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Vacancy</div>
                            <div>{{ $job->vacancy }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Employment type</div>
                            <div>{{ $job->employment_status }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Workplace</div>
                            <div>{{ $job->workplace }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Experience</div>
                            <div>{{ $job->experience_level }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Education</div>
                            <div>{{ $job->education_level }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Salary</div>
                            <div>{{ $job->salary_type }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Deadline</div>
                            <div>{{ $job->deadline->format('F j, Y') }}</div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="small text-muted">Published</div>
                            <div>{{ $job->published_at?->format('F j, Y') ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Description</h5>
                    <p class="mb-0">{{ $job->description }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Responsibilities</h5>
                    <p class="mb-0">{{ $job->responsibilities }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Requirements</h5>
                    <p class="mb-0">{{ $job->requirements }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Benefits</h5>
                    <p class="mb-0">{{ $job->benefits ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-soft mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Company information</h5>
                    @if(optional($job->company)->company_logo)
                        <div class="mb-3 text-center">
                            <img src="{{ $job->company->company_logo_url }}" alt="{{ $job->company->company_name }} logo" class="img-fluid rounded-4" style="max-height: 120px; object-fit: contain;">
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>{{ optional($job->company)->company_name ?? 'Company' }}</strong>
                    </div>
                    @if(optional($job->company)->industry)
                        <div class="mb-2">
                            <div class="small text-muted">Industry</div>
                            <div>{{ $job->company->industry }}</div>
                        </div>
                    @endif
                    @if(optional($job->company)->company_size)
                        <div class="mb-2">
                            <div class="small text-muted">Company size</div>
                            <div>{{ $job->company->company_size }}</div>
                        </div>
                    @endif
                    @if(optional($job->company)->website)
                        <div class="mb-2">
                            <div class="small text-muted">Website</div>
                            <div><a href="{{ $job->company->website }}" target="_blank" rel="noopener">{{ $job->company->website }}</a></div>
                        </div>
                    @endif
                    @if(optional($job->company)->address || optional($job->company)->city || optional($job->company)->country)
                        <div>
                            <div class="small text-muted">Address</div>
                            <div>
                                {{ optional($job->company)->address }}
                                {{ optional($job->company)->city ? ', ' . $job->company->city : '' }}
                                {{ optional($job->company)->country ? ', ' . $job->company->country : '' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @can('update', $job)
                <div class="card border-0 shadow-soft">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Manage job</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline-primary">Edit job</a>
                            <form action="{{ route('jobs.destroy', $job) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this job?')">Delete job</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection
