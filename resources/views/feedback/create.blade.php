@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 mb-3">Submit Feedback</h1>

                <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rating</label>
                            <input type="number" min="1" max="5" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', 5) }}" required>
                            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
