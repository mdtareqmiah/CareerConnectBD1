@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">User Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
</div>

@include('admin.users.partials.stats', ['stats' => $stats])
@include('admin.users.partials.filters', ['filters' => $filters, 'roles' => $roles])

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Login</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $roleSlug = $user->role?->slug;
                            $roleClass = match ($roleSlug) {
                                'admin' => 'danger',
                                'employer' => 'primary',
                                'job-seeker' => 'success',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $roleClass }}">{{ $user->role?->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        @if($user->trashed())
                            <span class="badge bg-dark">Deleted</span>
                        @elseif($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at?->format('M d, Y') }}</td>
                    <td>{{ $user->last_login_at?->format('M d, Y h:i A') ?? 'Never' }}</td>
                    <td class="text-end">
                        @include('admin.users.partials.actions', ['user' => $user])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No users found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
