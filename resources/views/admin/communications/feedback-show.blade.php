@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Feedback #{{ $feedback->id }}</h1>
    <a href="{{ route('admin.communications.index', ['tab' => 'feedback']) }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <p><strong>User:</strong> {{ $feedback->user?->name }} ({{ $feedback->user?->email }})</p>
        <p><strong>Subject:</strong> {{ $feedback->subject }}</p>
        <p><strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $feedback->type)) }}</p>
        <p><strong>Rating:</strong> {{ $feedback->rating }}/5</p>
        <p><strong>Message:</strong><br>{{ $feedback->message }}</p>
        @if($feedback->attachment_path)
            <p><a href="{{ asset('storage/'.$feedback->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Attachment</a></p>
        @endif

        <form method="POST" action="{{ route('admin.feedback.status', $feedback) }}" class="row g-2 mt-2">
            @csrf
            @method('PATCH')
            <div class="col-md-4">
                <select name="status" class="form-select">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected($feedback->status === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <button class="btn btn-primary">Update Status</button>
            </div>
        </form>
        <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger" onclick="return confirm('Delete this feedback?')">Delete</button>
        </form>
    </div>
</div>
@endsection
