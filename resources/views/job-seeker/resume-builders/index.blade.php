@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Resume Builder</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Resume Builder</h1>
            <p class="text-muted mb-0">Manage your in-app resume drafts and published builder profiles.</p>
        </div>
        <a href="{{ route('job-seeker.resume-builders.create') }}" class="btn btn-primary">Create Resume Builder</a>
    </div>

    @if($resumeBuilders->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="mb-3">No resume builders yet</h5>
                <p class="text-muted mb-4">Create a resume draft that you can update later.</p>
                <a href="{{ route('job-seeker.resume-builders.create') }}" class="btn btn-primary">Create Resume Builder</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($resumeBuilders as $builder)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h2 class="h5 mb-1">{{ $builder->title }}</h2>
                                    <div class="text-muted">Status: {{ ucfirst($builder->status) }}</div>
                                </div>
                                @if($builder->is_default)
                                    <span class="badge bg-success">Default</span>
                                @endif
                            </div>
                            @php
                                $summary = $builder->professional_summary ? \Illuminate\Support\Str::limit($builder->professional_summary, 120) : 'No professional summary provided yet.';
                            @endphp
                            <p class="text-muted mb-3">{{ $summary }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('job-seeker.resume-builders.edit', $builder) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form action="{{ route('job-seeker.resume-builders.destroy', $builder) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this resume builder?')">Delete</button>
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
