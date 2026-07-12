@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Experience</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                    <i class="bi bi-briefcase"></i>
                    <span class="fw-semibold">Career history</span>
                </div>
                <h1 class="h3 mb-1">Experience</h1>
                <p class="text-muted mb-0">Manage your professional background.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $experiences->count() }} entry{{ $experiences->count() === 1 ? '' : 'ies' }}</span>
                <a href="{{ route('job-seeker.experiences.create') }}" class="btn btn-primary">Add experience</a>
            </div>
        </div>

        @if ($experiences->isEmpty())
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                        <span class="display-6">🧾</span>
                    </div>
                    <h2 class="h5">No experience added yet</h2>
                    <p class="text-muted">Add your work history to showcase your experience to employers.</p>
                    <a href="{{ route('job-seeker.experiences.create') }}" class="btn btn-primary rounded-pill">Add experience</a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-soft rounded-4">
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
