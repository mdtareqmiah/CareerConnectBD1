@props(['action', 'method' => 'POST', 'resume' => null, 'submitLabel' => 'Upload Resume'])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="col-12">
        <label for="title" class="form-label">Resume Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $resume?->title) }}" required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="file_path" class="form-label">Resume File <span class="text-danger">*</span></label>
        <input type="file" class="form-control @error('file_path') is-invalid @enderror" id="file_path" name="file_path" {{ $resume ? '' : 'required' }}>
        @error('file_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($resume?->file_path)
            <div class="form-text">Current file: {{ $resume->file_name ?? basename($resume->file_path) }}</div>
        @endif
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $resume?->is_default) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">Set as default resume</label>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('job-seeker.resumes.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    </div>
</form>
