@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-2">Education</h1>
                <p class="text-muted mb-0">Manage your academic background.</p>
            </div>
            <a href="{{ route('job-seeker.educations.create') }}" class="btn btn-primary mt-3 mt-md-0">Add Education</a>
        </div>

        @if ($educations->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5">No education records yet</h2>
                    <p class="text-muted mb-3">Add your degrees and academic history to strengthen your profile.</p>
                    <a href="{{ route('job-seeker.educations.create') }}" class="btn btn-primary">Add Education</a>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Degree</th>
                                    <th>Institution</th>
                                    <th>Passing Year</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($educations as $education)
                                    <tr>
                                        <td>
                                            <strong>{{ $education->degree }}</strong><br>
                                            <small class="text-muted">{{ $education->field_of_study }}</small>
                                        </td>
                                        <td>{{ $education->institution_name }}</td>
                                        <td>{{ $education->passing_year ?: '-' }}</td>
                                        <td>{{ $education->result ?: '-' }}</td>
                                        <td>
                                            @if ($education->is_current)
                                                <span class="badge bg-success">Currently Studying</span>
                                            @else
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('job-seeker.educations.edit', $education) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteEducationModal{{ $education->id }}">Delete</button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="deleteEducationModal{{ $education->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Education</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this education record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('job-seeker.educations.destroy', $education) }}" method="POST">
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
