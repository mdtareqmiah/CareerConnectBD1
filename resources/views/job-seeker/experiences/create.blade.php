@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-2">Add Experience</h1>
                        <p class="text-muted mb-4">Share your job history with employers.</p>

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
