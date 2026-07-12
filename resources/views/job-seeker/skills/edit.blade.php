@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.skills.index') }}">Skills</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-soft rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                            <i class="bi bi-pencil-square"></i>
                            <span class="fw-semibold">Skill update</span>
                        </div>
                        <h1 class="h3 mb-2">Edit skill</h1>
                        <p class="text-muted mb-4">Update your skills and experience level.</p>

                        <div class="alert alert-light border rounded-4 mb-4" role="status">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                <div>
                                    <div class="fw-semibold">Keep it accurate</div>
                                    <div class="small text-muted">Refine the proficiency and experience details so employers see your strongest fit.</div>
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

                        @include('job-seeker.skills.partials.form', [
                            'action' => route('job-seeker.skills.update', $skill),
                            'method' => 'PATCH',
                            'skill' => $skill,
                            'submitLabel' => 'Update Skill',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
