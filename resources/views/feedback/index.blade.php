@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">My Feedback</h1>
        <p class="text-muted mb-0">Track suggestion, complaint, bug report, and feature feedback status.</p>
    </div>
    <a href="{{ route('feedback.create') }}" class="btn btn-primary">Submit Feedback</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbackItems as $item)
                        <tr>
                            <td>{{ $item->subject }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->type)) }}</td>
                            <td>{{ $item->rating }}/5</td>
                            <td>
                                <span class="badge {{ $item->status === 'resolved' ? 'bg-success' : ($item->status === 'closed' ? 'bg-secondary' : ($item->status === 'in_review' ? 'bg-info text-dark' : 'bg-warning text-dark')) }}">
                                    {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="text-end"><a href="{{ route('feedback.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No feedback submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $feedbackItems->links() }}</div>
@endsection
