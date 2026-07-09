@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-2">Skills</h1>
                <p class="text-muted mb-0">Manage your technical and professional skills.</p>
            </div>
            <a href="{{ route('job-seeker.skills.create') }}" class="btn btn-primary mt-3 mt-md-0">Add Skill</a>
        </div>

        @if ($skills->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="display-6 mb-3">🛠️</div>
                    <h2 class="h5 mb-2">No skills added yet</h2>
                    <p class="text-muted mb-4">Add your key skills to strengthen your profile.</p>
                    <a href="{{ route('job-seeker.skills.create') }}" class="btn btn-primary">Add Skill</a>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                            <a href="{{ route('job-seeker.skills.edit', $skill) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSkillModal{{ $skill->id }}">Delete</button>
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
