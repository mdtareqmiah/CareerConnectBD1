@extends('layouts.app')

@section('content')
<div id="jobs-page-root"></div>

<script>
    window.__JOBS_DATA__ = {
        jobs: @json($jobs->items()),
        jobTypes: @json($jobTypes),
        pagination: {
            current_page: {{ $jobs->currentPage() }},
            last_page: {{ $jobs->lastPage() }},
            total: {{ $jobs->total() }},
            per_page: {{ $jobs->perPage() }},
        }
    };
</script>
@endsection
