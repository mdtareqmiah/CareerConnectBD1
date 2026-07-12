@extends('layouts.app')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Skills</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                    <i class="bi bi-tools"></i>
                    <span class="fw-semibold">Skill profile</span>
                </div>
                <h1 class="h3 mb-1">Skills</h1>
                <p class="text-muted mb-0">Manage your technical and professional skills.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $skills->count() }} skill{{ $skills->count() === 1 ? '' : 's' }}</span>
                <a href="{{ route('job-seeker.skills.create') }}" class="btn btn-primary">Add skill</a>
            </div>
        </div>

        @if ($skills->isEmpty())
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
                        <span class="display-6">🛠️</span>
                    </div>
                    <h2 class="h5">No skills added yet</h2>
                    <p class="text-muted">Add your key skills to strengthen your profile and signal fit clearly.</p>
                    <a href="{{ route('job-seeker.skills.create') }}" class="btn btn-primary rounded-pill">Add skill</a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-soft rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Skill</th>
                                    <th>Proficiency</th>
                                    <th>Years</th>
                                    <th>Notes</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skills as $skill)
                                    <tr>
                                        <td><strong>{{ $skill->skill_name }}</strong></td>
                                        <td>{{ $skill->proficiency_level }}</td>
                                        <td>{{ $skill->years_of_experience ?? '-' }}</td>
                                        <td>{{ $skill->notes ?: '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('job-seeker.skills.edit', $skill) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSkillModal{{ $skill->id }}">Delete</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="deleteSkillModal{{ $skill->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Skill</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this skill record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('job-seeker.skills.destroy', $skill) }}" method="POST">
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
