@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 mb-1">Invitation Details</h2>
                        <p class="text-muted mb-0">{{ $invitation->job->title ?? 'Interview Invitation' }}</p>
                    </div>
                    <span class="badge bg-info text-dark">{{ ucfirst($invitation->status) }}</span>
                </div>

                <dl class="row">
                    <dt class="col-sm-4">Candidate</dt>
                    <dd class="col-sm-8">{{ $invitation->candidate->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Employer</dt>
                    <dd class="col-sm-8">{{ $invitation->employer->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Scheduled</dt>
                    <dd class="col-sm-8">{{ $invitation->interview_at->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Location</dt>
                    <dd class="col-sm-8">{{ $invitation->location ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Meeting Link</dt>
                    <dd class="col-sm-8">{{ $invitation->meeting_link ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Notes</dt>
                    <dd class="col-sm-8">{{ $invitation->notes ?? 'N/A' }}</dd>
                </dl>

                @if (auth()->user()?->role?->slug === 'job-seeker' && $invitation->status === 'pending')
                    <form method="POST" action="{{ route('job-seeker.interview-invitations.respond', $invitation) }}" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <div class="d-flex gap-2">
                            <button name="response" value="accepted" class="btn btn-success">Accept</button>
                            <button name="response" value="declined" class="btn btn-outline-danger">Decline</button>
                        </div>
                    </form>
                @endif

                @if (auth()->user()?->role?->slug === 'employer' && $invitation->status !== 'cancelled')
                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('employer.interview-invitations.index') }}" class="btn btn-outline-secondary">Back</a>
                        <form method="POST" action="{{ route('employer.interview-invitations.destroy', $invitation) }}" onsubmit="return confirm('Cancel this invitation?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger">Cancel</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
