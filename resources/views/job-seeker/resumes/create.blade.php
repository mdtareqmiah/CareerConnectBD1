@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.resumes.index') }}">Resume</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-2">Upload Resume</h1>
                        <p class="text-muted mb-4">Upload a PDF, DOC, or DOCX resume.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('job-seeker.resumes.partials.form', [
                            'action' => route('job-seeker.resumes.store'),
                            'method' => 'POST',
                            'submitLabel' => 'Upload Resume',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
