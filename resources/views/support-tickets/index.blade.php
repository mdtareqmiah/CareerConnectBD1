@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Support Tickets</h1>
        <p class="text-muted mb-0">Track your ticket statuses and ongoing support replies.</p>
    </div>
    <a href="{{ route('support-tickets.create') }}" class="btn btn-primary">Create Ticket</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ticket</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Reply</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}<div class="small text-muted">{{ $ticket->subject }}</div></td>
                            <td>{{ ucwords($ticket->category) }}</td>
                            <td><span class="badge {{ $ticket->priority === 'urgent' ? 'bg-danger' : ($ticket->priority === 'high' ? 'bg-warning text-dark' : ($ticket->priority === 'medium' ? 'bg-info text-dark' : 'bg-secondary')) }}">{{ ucfirst($ticket->priority) }}</span></td>
                            <td><span class="badge {{ in_array($ticket->status, ['resolved', 'closed']) ? 'bg-success' : 'bg-primary' }}">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</span></td>
                            <td>{{ $ticket->last_reply_at?->diffForHumans() ?? '-' }}</td>
                            <td class="text-end"><a href="{{ route('support-tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No support tickets created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $tickets->links() }}</div>
@endsection
