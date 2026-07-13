@extends('layouts.app')

@section('title', 'Saved Jobs')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Saved Jobs</h1>
            <p class="text-muted">Review jobs you have saved for later.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Browse Jobs</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($savedJobs->isEmpty())
                <div class="p-4 text-center text-muted">No saved jobs yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Job</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Saved</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($savedJobs as $savedJob)
                                <tr>
                                    <td>
                                        <a href="{{ route('jobs.show', $savedJob->job) }}" class="fw-semibold">{{ $savedJob->job->title }}</a>
                                    </td>
                                    <td>{{ optional($savedJob->job->company)->company_name ?? 'Company' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $savedJob->job->status_badge_color }} text-uppercase">{{ $savedJob->job->display_status }}</span>
                                    </td>
                                    <td>{{ $savedJob->created_at->diffForHumans() }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('jobs.saved.toggle', $savedJob->job) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $savedJobs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
