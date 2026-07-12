@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                <i class="bi bi-trash3"></i>
                <span class="fw-semibold">Trashed jobs</span>
            </div>
            <h1 class="h3 mb-1">Trashed jobs</h1>
            <p class="text-muted mb-0">Restore or permanently delete soft deleted job postings.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Back to jobs</a>
    </div>

    <div class="row g-4">
        <div class="col-12">
            @forelse ($jobs as $job)
                <div class="card border-0 shadow-soft mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $job->title }}</h5>
                                <p class="text-muted mb-1">{{ $job->location }} · {{ $job->job_type }} · {{ ucfirst($job->status) }}</p>
                                <p class="mb-0">Deleted at: {{ $job->deleted_at?->format('F j, Y h:i A') }}</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <form action="{{ route('jobs.restore', $job) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                </form>
                                <form action="{{ route('jobs.forceDelete', $job) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this job?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-soft overflow-hidden">
                    <div class="card-body text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-trash3"></i>
                        </div>
                        <h5 class="mb-2">No trashed jobs</h5>
                        <p class="text-muted mb-0">There are no soft-deleted job postings to restore right now.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
