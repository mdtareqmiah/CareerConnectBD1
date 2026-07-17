@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h1 class="h4 mb-2">{{ $title }}</h1>
                <p class="text-muted mb-0">This section is currently being prepared for the next content-management update. The interface is live, and the content workflow will be completed in the upcoming milestone.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">In progress</span>
        </div>
    </div>
</div>
@endsection
