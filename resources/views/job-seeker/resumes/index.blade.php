@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Resume</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                    <i class="bi bi-file-earmark-richtext"></i>
                    <span class="fw-semibold">Resume library</span>
                </div>
                <h1 class="h3 mb-1">Resumes</h1>
                <p class="text-muted mb-0">Upload and manage your resume documents.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $resumes->count() }} resume{{ $resumes->count() === 1 ? '' : 's' }}</span>
                <a href="{{ route('job-seeker.resumes.create') }}" class="btn btn-primary">Upload resume</a>
            </div>
        </div>

        @if ($resumes->isEmpty())
            <div class="card border-0 shadow-soft overflow-hidden">
                <div class="card-body text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                        <span class="display-6">📄</span>
                    </div>
                    <h2 class="h5">No resumes uploaded yet</h2>
                    <p class="text-muted">Add your latest resume to make a strong impression across recruiter workflows.</p>
                    <a href="{{ route('job-seeker.resumes.create') }}" class="btn btn-primary">Upload resume</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($resumes as $resume)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-soft h-100">
                            <div class="card-body p-4">
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
