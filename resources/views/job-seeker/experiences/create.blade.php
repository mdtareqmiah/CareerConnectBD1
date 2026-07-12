@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.experiences.index') }}">Experience</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-soft rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                            <i class="bi bi-plus-circle"></i>
                            <span class="fw-semibold">Experience entry</span>
                        </div>
                        <h1 class="h3 mb-2">Add experience</h1>
                        <p class="text-muted mb-4">Share your job history with employers.</p>

                        <div class="alert alert-light border rounded-4 mb-4" role="status">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                <div>
                                    <div class="fw-semibold">Showcase your experience</div>
                                    <div class="small text-muted">Add the roles and responsibilities that best describe your professional path.</div>
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
                            'action' => route('job-seeker.experiences.store'),
                            'method' => 'POST',
                            'submitLabel' => 'Save Experience',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
