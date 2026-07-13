@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('job-seeker.resume-builders.index') }}">Resume Builder</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preview</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Resume Preview</h1>
            <p class="text-muted mb-0">Preview your resume in different templates and download a PDF.</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('resume-builder.download', ['resumeBuilder' => $builder, 'template' => $template]) }}" class="btn btn-primary">Download PDF</a>
            <a href="{{ route('resume-builder.print', ['resumeBuilder' => $builder, 'template' => $template]) }}" target="_blank" class="btn btn-outline-secondary">Print Resume</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-6">
                    <label class="form-label">Template</label>
                    <select id="template_selector" class="form-select">
                        @foreach($templates as $option)
                            <option value="{{ $option }}" @selected($option === $template)>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Resume Title</label>
                    <input type="text" class="form-control" value="{{ $builder->title }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <iframe id="preview_frame" src="{{ route('resume-builder.print', ['resumeBuilder' => $builder, 'template' => $template]) }}" class="w-100" style="min-height: 900px; border:none;"></iframe>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('template_selector').addEventListener('change', function () {
        const template = this.value;
        const builderId = '{{ $builder->id }}';
        const previewUrl = `{{ url('/job-seeker/resume-builders') }}/${builderId}/print?template=${template}`;
        const downloadUrl = `{{ url('/job-seeker/resume-builders') }}/${builderId}/download?template=${template}`;

        document.getElementById('preview_frame').src = previewUrl;
        document.querySelector('a.btn-primary').href = downloadUrl;
        document.querySelector('a.btn-outline-secondary').href = previewUrl;
    });
</script>
@endsection
