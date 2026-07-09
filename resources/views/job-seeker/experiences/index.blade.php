@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Experience</li>
            </ol>
        </nav>

        <x-page-header title="Experience" description="Manage your professional background.">
            <a href="{{ route('job-seeker.experiences.create') }}" class="btn btn-primary">Add Experience</a>
        </x-page-header>

        @if ($experiences->isEmpty())
            <x-empty-state-card title="No experience added yet" description="Add your work history to showcase your experience to employers." action-label="Add Experience" action-route="{{ route('job-seeker.experiences.create') }}" icon="🧾" />
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Company</th>
                                    <th>Job Title</th>
                                    <th>Employment Type</th>
                                    <th>Location</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($experiences as $experience)
                                    <tr>
                                        <td><strong>{{ $experience->company_name }}</strong></td>
                                        <td>{{ $experience->job_title }}</td>
                                        <td>{{ $experience->employment_type }}</td>
                                        <td>{{ $experience->location ?: '-' }}</td>
                                        <td>{{ optional($experience->start_date)->format('M Y') }}</td>
                                        <td>{{ $experience->currently_working ? 'Present' : optional($experience->end_date)->format('M Y') }}</td>
                                        <td>
                                            @if ($experience->currently_working)
                                                <span class="badge bg-success">Current</span>
                                            @else
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('job-seeker.experiences.edit', $experience) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteExperienceModal{{ $experience->id }}">Delete</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="deleteExperienceModal{{ $experience->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Experience</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this experience record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('job-seeker.experiences.destroy', $experience) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
