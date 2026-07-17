@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">{{ $ticket->ticket_number }}</h1>
        <p class="text-muted mb-0">{{ $ticket->subject }}</p>
    </div>
    <a href="{{ route('support-tickets.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body d-flex flex-wrap gap-2">
        <span class="badge bg-secondary">Category: {{ ucwords($ticket->category) }}</span>
        <span class="badge {{ $ticket->priority === 'urgent' ? 'bg-danger' : ($ticket->priority === 'high' ? 'bg-warning text-dark' : ($ticket->priority === 'medium' ? 'bg-info text-dark' : 'bg-secondary')) }}">Priority: {{ ucfirst($ticket->priority) }}</span>
        <span class="badge {{ in_array($ticket->status, ['resolved', 'closed']) ? 'bg-success' : 'bg-primary' }}">Status: {{ ucwords(str_replace('_', ' ', $ticket->status)) }}</span>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3" style="max-height: 480px; overflow-y: auto;" id="ticketThread">
    <div class="card-body">
        @forelse($ticket->messages->sortBy('created_at') as $message)
            <div class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                <div class="p-3 rounded {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                    <div class="small fw-semibold mb-1">{{ $message->sender?->name ?? 'Unknown' }}</div>
                    <div>{{ $message->message }}</div>
                    @if($message->attachment_path)
                        <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank" class="small {{ $message->sender_id === auth()->id() ? 'text-white' : 'text-primary' }} d-block mt-1">View attachment</a>
                    @endif
                    <div class="small mt-1 opacity-75">{{ $message->created_at?->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="text-muted text-center py-4">No conversation yet.</div>
        @endforelse
    </div>
</div>

@if($ticket->status !== 'closed')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('support-tickets.replies.store', $ticket) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Reply</label>
                <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Attachment (Optional)</label>
                <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="text-end">
                <button class="btn btn-primary" type="submit">Send Reply</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    const thread = document.getElementById('ticketThread');
    if (thread) {
        thread.scrollTop = thread.scrollHeight;
    }
</script>
@endsection
