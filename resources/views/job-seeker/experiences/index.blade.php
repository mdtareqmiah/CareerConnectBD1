@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-2">Experience</h1>
                <p class="text-muted mb-0">Manage your professional background.</p>
            </div>
            <a href="{{ route('job-seeker.experiences.create') }}" class="btn btn-primary mt-3 mt-md-0">Add Experience</a>
        </div>

        @if ($experiences->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="display-6 mb-3">🧾</div>
                    <h2 class="h5 mb-2">No experience added yet</h2>
                    <p class="text-muted mb-4">Add your work history to showcase your experience to employers.</p>
                    <a href="{{ route('job-seeker.experiences.create') }}" class="btn btn-primary">Add Experience</a>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                            <a href="{{ route('job-seeker.experiences.edit', $experience) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteExperienceModal{{ $experience->id }}">Delete</button>
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
