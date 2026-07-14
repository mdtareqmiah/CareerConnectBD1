@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 mb-3">Create Support Ticket</h1>
                <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" required>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attachment (Optional)</label>
                            <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                            @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('support-tickets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
