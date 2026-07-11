@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Trashed Jobs</h1>
        <p class="text-muted mb-0">Restore or permanently delete soft deleted job postings.</p>
    </div>
    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Back to Jobs</a>
</div>

<div class="row g-4">
    <div class="col-12">
        @forelse ($jobs as $job)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">{{ $job->title }}</h5>
                            <p class="text-muted mb-1">{{ $job->location }} · {{ $job->job_type }} · {{ ucfirst($job->status) }}</p>
                            <p class="mb-0">Deleted at: {{ $job->deleted_at?->format('F j, Y h:i A') }}</p>
                        </div>
                        <div class="text-end">
                            <form action="{{ route('jobs.restore', $job) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Restore</button>
                            </form>
                            <form action="{{ route('jobs.forceDelete', $job) }}" method="POST" class="d-inline-block ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this job?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                No trashed jobs found.
            </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection
