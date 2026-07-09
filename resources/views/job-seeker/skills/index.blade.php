@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('job-seeker.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Skills</li>
            </ol>
        </nav>

        <x-page-header title="Skills" description="Manage your technical and professional skills.">
            <a href="{{ route('job-seeker.skills.create') }}" class="btn btn-primary">Add Skill</a>
        </x-page-header>

        @if ($skills->isEmpty())
            <x-empty-state-card title="No skills added yet" description="Add your key skills to strengthen your profile." action-label="Add Skill" action-route="{{ route('job-seeker.skills.create') }}" icon="🛠️" />
        @else
            <div class="card shadow-sm border-0">
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
