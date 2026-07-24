@extends('layouts.app')

@section('content')

<div
    id="employer-dashboard"
    data-user='@json($user)'
    data-company='@json($company)'
    data-stats='@json($stats)'
    data-job-stats='@json($jobStats)'
    data-application-stats='@json($applicationStats)'
    data-recent-jobs='@json($recentJobs)'
></div>

@endsection