@extends('layouts.app')

@section('title', 'Applicant Review')

@section('content')
<div class="container py-4">
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div>
            <h1 class="h3 mb-1">Applicant Review</h1>
            <p class="text-muted mb-0">Recruiter dashboard for reviewing applicants, resumes, and hiring decisions.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('jobs.show', $jobApplication->job) }}" class="btn btn-outline-primary">View Public Job</a>
            <a href="{{ route('company.edit', $jobApplication->job->company) }}" class="btn btn-outline-secondary">View Company</a>
            <a href="{{ route('employer.applications.index') }}" class="btn btn-secondary">Back to Applications</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row gy-4">
        <div class="col-xl-8">
            @php $profile = $jobApplication->user->jobSeekerProfile; @endphp
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $profile?->profile_photo_url ?? asset('images/default-avatar.svg') }}" alt="Profile photo" class="rounded-circle border" width="96" height="96">
                        <div>
                            <h2 class="h4 mb-1">{{ $profile?->first_name ?? $jobApplication->user->name }} {{ $profile?->last_name ?? '' }}</h2>
                            <p class="text-muted mb-1">{{ $jobApplication->user->email }}</p>
                            <p class="text-muted mb-0">{{ $profile?->phone ?? 'No phone available' }}</p>
                        </div>
                    </div>

                    <div class="row gy-3 mb-4">
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-uppercase text-muted small mb-2">Address</div>
                                <div>{{ $profile?->address ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-uppercase text-muted small mb-2">Gender</div>
                                <div>{{ $profile?->gender ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-uppercase text-muted small mb-2">Date of Birth</div>
                                <div>{{ optional($profile?->date_of_birth)->format('M d, Y') ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-uppercase text-muted small mb-2">Profile Completion</div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $profileCompletion }}%;" aria-valuenow="{{ $profileCompletion }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="fw-semibold">{{ $profileCompletion }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h6 mb-3">Education</h3>
                            @if(! $profile || $profile->educations->isEmpty())
                                <div class="border rounded-3 p-4 text-center text-muted">No education records available.</div>
                            @else
                                @foreach($profile->educations as $education)
                                    <div class="mb-4">
                                        <div class="fw-semibold">{{ $education->degree }}</div>
                                        <div class="small text-muted">{{ $education->institution_name }} &bull; {{ $education->board_or_university }}</div>
                                        <div class="small">Result: {{ $education->result ?? 'N/A' }}</div>
                                        <div class="small">Passing Year: {{ $education->passing_year ?? 'N/A' }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h6 mb-3">Experience</h3>
                            @if(!$profile || $profile->experiences->isEmpty())
                                <div class="border rounded-3 p-4 text-center text-muted">No experience records available.</div>
                            @else
                                @foreach($profile->experiences as $experience)
                                    <div class="mb-4">
                                        <div class="fw-semibold">{{ $experience->company_name }}</div>
                                        <div class="small text-muted">{{ $experience->job_title }}</div>
                                        <div class="small">{{ optional($experience->start_date)->format('M Y') ?? 'N/A' }} - {{ $experience->currently_working ? 'Present' : optional($experience->end_date)->format('M Y') ?? 'N/A' }}</div>
                                        <div class="small">{{ $experience->job_description ?? 'No responsibilities provided.' }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Skills</h3>
                    @if(!$profile || $profile->skills->isEmpty())
                        <div class="border rounded-3 p-4 text-center text-muted">No skills added yet.</div>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($profile->skills as $skill)
                                <span class="badge bg-secondary">{{ $skill->skill_name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Resume</h3>
                    @if(! $jobApplication->resume)
                        <div class="border rounded-3 p-4 text-center text-muted">No resume uploaded.</div>
                    @else
                        <div class="mb-3">
                            <div class="fw-semibold">{{ $jobApplication->resume->file_name ?? basename($jobApplication->resume->file_path) }}</div>
                            <div class="small text-muted">Uploaded {{ optional($jobApplication->resume->uploaded_at)->format('M d, Y') ?? 'Unknown' }}</div>
                            <div class="small text-muted">{{ strtoupper($jobApplication->resume->file_type) }} &bull; {{ number_format($jobApplication->resume->file_size / 1024, 1) }} KB</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($jobApplication->resume->file_type === 'pdf')
                                <a href="{{ route('employer.applications.resume.preview', $jobApplication) }}" target="_blank" class="btn btn-outline-primary">Preview Resume</a>
                            @endif
                            <a href="{{ route('employer.applications.resume.download', $jobApplication) }}" class="btn btn-primary">Download Resume</a>
                        </div>

                        @if(! empty($resumeAnalysis))
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="border rounded-3 p-3 bg-light">
                                        <div class="small text-muted">ATS Score</div>
                                        <div class="fw-semibold">{{ $resumeAnalysis['atsScore'] ?? 0 }}%</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-3 p-3 bg-light">
                                        <div class="small text-muted">ATS Readiness</div>
                                        <div class="fw-semibold">{{ $resumeAnalysis['estimatedATS'] ?? 'Low' }}</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded-3 p-3 bg-light">
                                        <div class="small text-muted">ATS Keywords</div>
                                        <div class="fw-semibold">{{ $resumeAnalysis['keywordCount'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            @if(! empty($matchedSkills) || ! empty($missingSkills))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Skill Match</h3>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="small text-muted mb-2">Matched Skills</div>
                                    @if($matchedSkills->isEmpty())
                                        <div class="text-muted">No matched skills found.</div>
                                    @else
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($matchedSkills as $skill)
                                                <span class="badge bg-success">{{ ucfirst($skill) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="small text-muted mb-2">Missing Skills</div>
                                    @if($missingSkills->isEmpty())
                                        <div class="text-muted">No missing skills detected.</div>
                                    @else
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($missingSkills as $skill)
                                                <span class="badge bg-warning text-dark">{{ ucfirst($skill) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-4">
            <div class="position-sticky" style="top: 1rem;">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Update Application Status</h3>
                        <form method="POST" action="{{ route('employer.applications.update_status', $jobApplication) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    @foreach(\App\Models\JobApplication::statusOptions() as $status => $label)
                                        <option value="{{ $status }}" {{ $jobApplication->status === $status ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Status</button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Job Information</h3>
                        <div class="mb-3">
                            <div class="fw-semibold">{{ $jobApplication->job->title }}</div>
                            <div class="small text-muted">{{ $jobApplication->job->company->company_name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Applied Date</div>
                            <div>{{ optional($jobApplication->applied_at)->format('M d, Y') ?? $jobApplication->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Current Status</div>
                            <div>{!! $jobApplication->statusBadge() !!}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Cover Letter</div>
                            <div class="text-break">{{ $jobApplication->cover_letter ?: 'No cover letter provided.' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Application Timeline</h3>
                        <div class="timeline">
                            @foreach($timeline as $event)
                                <div class="d-flex mb-3">
                                    <div class="me-3 mt-1">
                                        <span class="badge rounded-circle bg-primary" style="width: 12px; height: 12px;"></span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $event['label'] }}</div>
                                        <div class="small text-muted">{{ $event['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Quick Actions</h3>
                        <div class="d-grid gap-2">
                            @if($jobApplication->resume && $jobApplication->resume->file_type === 'pdf')
                                <a href="{{ route('employer.applications.resume.preview', $jobApplication) }}" target="_blank" class="btn btn-outline-primary">Preview Resume</a>
                            @endif
                            @if($jobApplication->resume)
                                <a href="{{ route('employer.applications.resume.download', $jobApplication) }}" class="btn btn-primary">Download Resume</a>
                            @endif
                            <a href="{{ route('jobs.show', $jobApplication->job) }}" class="btn btn-outline-secondary">View Public Job</a>
                            <a href="{{ route('company.show', $jobApplication->job->company) }}" class="btn btn-outline-secondary">View Company</a>
                            <a href="{{ route('employer.applications.index') }}" class="btn btn-secondary">Back to Applications</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
