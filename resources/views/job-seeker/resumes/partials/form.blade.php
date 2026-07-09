@props(['action', 'method' => 'POST', 'resume' => null, 'submitLabel' => 'Upload Resume'])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="col-12">
        <label for="title" class="form-label">Resume Title</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $resume?->title) }}" required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="resume_file" class="form-label">Resume File</label>
        <input type="file" class="form-control @error('resume_file') is-invalid @enderror" id="resume_file" name="resume_file" {{ $resume ? '' : 'required' }}>
        @error('resume_file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($resume?->resume_file)
            <div class="form-text">Current file: {{ $resume->file_name ?? basename($resume->resume_file) }}</div>
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
