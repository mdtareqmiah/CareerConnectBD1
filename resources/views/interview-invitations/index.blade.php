@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                    <div>
                        <h2 class="h4 mb-1">Interview Invitations</h2>
                        <p class="text-muted mb-0">Manage invitations and keep track of responses.</p>
                    </div>
                    @if (auth()->user()?->role?->slug === 'employer')
                        <a href="{{ route('employer.interview-invitations.create') }}" class="btn btn-primary btn-sm">Create Invitation</a>
                    @endif
                </div>

                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by job title">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            @foreach (['pending','accepted','declined','cancelled'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>

                @if ($invitations->isEmpty())
                    <div class="alert alert-light border">No invitations found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Job</th>
                                    <th>Scheduled</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invitations as $invitation)
                                    <tr>
                                        <td>{{ $invitation->candidate->name ?? 'N/A' }}</td>
                                        <td>{{ $invitation->job->title ?? 'N/A' }}</td>
                                        <td>{{ $invitation->interview_at->format('M d, Y H:i') }}</td>
                                        <td><span class="badge bg-info text-dark">{{ ucfirst($invitation->status) }}</span></td>
                                        <td>
                                            <a href="{{ route(auth()->user()?->role?->slug === 'employer' ? 'employer.interview-invitations.show' : 'job-seeker.interview-invitations.show', $invitation) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $invitations->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
