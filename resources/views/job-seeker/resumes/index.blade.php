@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Resume</li>
            </ol>
        </nav>

        <x-page-header title="Resumes" description="Upload and manage your resume documents.">
            <a href="{{ route('job-seeker.resumes.create') }}" class="btn btn-primary">Upload Resume</a>
        </x-page-header>

        @if ($resumes->isEmpty())
            <x-empty-state-card title="No resumes uploaded yet" description="Add your latest resume to make a strong impression." action-label="Upload Resume" action-route="{{ route('job-seeker.resumes.create') }}" icon="📄" />
        @else
            <div class="row g-4">
                @foreach ($resumes as $resume)
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h2 class="h5 mb-1">{{ $resume->title }}</h2>
                                        <p class="text-muted mb-0">{{ $resume->file_name ?? basename($resume->file_path) }}</p>
                                    </div>
                                    @if ($resume->is_default)
                                        <span class="badge bg-success">Default</span>
                                    @endif
                                </div>

                                <div class="small text-muted mb-3">
                                    <div>Type: {{ strtoupper($resume->file_type ?? 'file') }}</div>
                                    <div>Size: {{ number_format(($resume->file_size ?? 0) / 1024, 1) }} KB</div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('job-seeker.resumes.download', $resume) }}" class="btn btn-outline-primary btn-sm">Download</a>
                                    <a href="{{ route('job-seeker.resumes.edit', $resume) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteResumeModal{{ $resume->id }}">Delete</button>
                                </div>

                                @php
                                    $analysis = $resumeAnalysis[$resume->id] ?? null;
                                    $resumeScore = (int) data_get($analysis, 'resume_score', 0);
                                    $readiness = data_get($analysis, 'readiness', 'Incomplete');
                                    $keywordCount = (int) data_get($analysis, 'keywordCount', 0);
                                    $estimatedATS = data_get($analysis, 'estimatedATS', 'Low');
                                    $lastAnalyzed = data_get($analysis, 'lastAnalyzed', 'N/A');
                                    $strengths = data_get($analysis, 'strengths', []);
                                    $warnings = data_get($analysis, 'warnings', []);
                                    $suggestions = data_get($analysis, 'suggestions', []);
                                @endphp

                                @if(! empty($analysis))
                                    <div class="mt-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h3 class="h6">Resume Analysis</h3>
                                                <div class="row g-2 align-items-center mb-3">
                                                    <div class="col-auto text-muted small">Score:</div>
                                                    <div class="col-auto">
                                                        <div class="fs-3 fw-bold">{{ $resumeScore }}%</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $resumeScore }}%;" aria-valuenow="{{ $resumeScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge bg-{{ $readiness === 'Excellent' ? 'success' : ($readiness === 'Good' ? 'primary' : ($readiness === 'Needs Improvement' ? 'warning' : 'danger')) }} text-uppercase">{{ $readiness }}</span>
                                                    </div>
                                                </div>

                                                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                                                    <div class="col">
                                                        <div class="border rounded-3 p-3 bg-white">
                                                            <div class="small text-muted">ATS Keywords</div>
                                                            <div class="fw-semibold">{{ $keywordCount }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="border rounded-3 p-3 bg-white">
                                                            <div class="small text-muted">ATS Readiness</div>
                                                            <div class="fw-semibold">{{ $estimatedATS }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3 text-muted small">Last analyzed: {{ $lastAnalyzed }}</div>

                                                @if(! empty($strengths))
                                                    <div class="mb-3">
                                                        <h4 class="h6 mb-2">Strengths</h4>
                                                        <ul class="mb-0">
                                                            @foreach($strengths as $strength)
                                                                <li>{{ $strength }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(! empty($warnings))
                                                    <div class="mb-3">
                                                        <h4 class="h6 mb-2">Warnings</h4>
                                                        <ul class="mb-0">
                                                            @foreach($warnings as $warning)
                                                                <li>{{ $warning }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(! empty($suggestions))
                                                    <div>
                                                        <h4 class="h6 mb-2">Suggestions</h4>
                                                        <ul class="mb-0">
                                                            @foreach($suggestions as $suggestion)
                                                                <li>{{ $suggestion }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteResumeModal{{ $resume->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Resume</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">Are you sure you want to delete this resume?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('job-seeker.resumes.destroy', $resume) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
