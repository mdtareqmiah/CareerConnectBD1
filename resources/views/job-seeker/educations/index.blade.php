@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Education</li>
            </ol>
        </nav>

        <x-page-header title="Education" description="Manage your academic background.">
            <a href="{{ route('job-seeker.educations.create') }}" class="btn btn-primary">Add Education</a>
        </x-page-header>

        @if ($educations->isEmpty())
            <x-empty-state-card title="No education records yet" description="Add your degrees and academic history to strengthen your profile." action-label="Add Education" action-route="{{ route('job-seeker.educations.create') }}" icon="🎓" />
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
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
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('job-seeker.educations.edit', $education) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteEducationModal{{ $education->id }}">Delete</button>
                                            </div>
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
