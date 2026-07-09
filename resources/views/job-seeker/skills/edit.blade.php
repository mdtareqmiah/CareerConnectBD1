@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.skills.index') }}">Skills</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-2">Edit Skill</h1>
                        <p class="text-muted mb-4">Update your skills and experience level.</p>

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
