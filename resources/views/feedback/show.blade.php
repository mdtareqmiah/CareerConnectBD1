@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Feedback Details</h1>
    <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="mb-3"><strong>Subject:</strong> {{ $feedback->subject }}</div>
        <div class="mb-3"><strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $feedback->type)) }}</div>
        <div class="mb-3"><strong>Rating:</strong> {{ $feedback->rating }}/5</div>
        <div class="mb-3"><strong>Status:</strong>
            <span class="badge {{ $feedback->status === 'resolved' ? 'bg-success' : ($feedback->status === 'closed' ? 'bg-secondary' : ($feedback->status === 'in_review' ? 'bg-info text-dark' : 'bg-warning text-dark')) }}">
                {{ ucwords(str_replace('_', ' ', $feedback->status)) }}
            </span>
        </div>
        <div class="mb-3"><strong>Message:</strong><p class="mt-2 mb-0">{{ $feedback->message }}</p></div>
        @if($feedback->attachment_path)
            <div><a href="{{ asset('storage/'.$feedback->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Attachment</a></div>
        @endif
    </div>
</div>
@endsection
