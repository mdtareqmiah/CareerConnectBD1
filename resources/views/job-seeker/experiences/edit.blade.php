@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="h3 mb-2">Edit Experience</h1>
                        <p class="text-muted mb-4">Update your professional history.</p>

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
