@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.experiences.index') }}">Experience</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-soft rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                            <i class="bi bi-pencil-square"></i>
                            <span class="fw-semibold">Experience update</span>
                        </div>
                        <h1 class="h3 mb-2">Edit experience</h1>
                        <p class="text-muted mb-4">Update your professional history.</p>

                        <div class="alert alert-light border rounded-4 mb-4" role="status">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                <div>
                                    <div class="fw-semibold">Keep it current</div>
                                    <div class="small text-muted">Refresh your history so your profile remains relevant to recruiters.</div>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('job-seeker.experiences.partials.form', [
                            'action' => route('job-seeker.experiences.update', $experience),
                            'method' => 'PATCH',
                            'experience' => $experience,
                            'submitLabel' => 'Update Experience',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
