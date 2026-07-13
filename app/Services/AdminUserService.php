<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminUserService
{
    public function getListingData(array $filters): array
    {
        return [
            'users' => $this->getUsers($filters),
            'roles' => $this->getAvailableRoles(),
            'stats' => $this->getStats(),
        ];
    }

    public function getAvailableRoles()
    {
        return Role::query()
            ->whereIn('slug', ['admin', 'employer', 'job-seeker'])
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => (int) $data['role_id'],
            'is_active' => (bool) $data['is_active'],
        ]);
    }

    public function updateUser(User $user, array $data, User $actor): User
    {
        $this->ensureSelfProtection($user, $data, $actor);
        $this->ensureRoleChangeIsSafe($user, (int) $data['role_id']);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => (int) $data['role_id'],
            'is_active' => (bool) $data['is_active'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user->refresh();
    }

    public function toggleStatus(User $user, User $actor): User
    {
        if ($user->trashed()) {
            throw ValidationException::withMessages([
                'status' => 'Cannot change status of a deleted user. Please restore first.',
            ]);
        }

        if ($user->id === $actor->id && $user->is_active) {
            throw ValidationException::withMessages([
                'status' => 'You cannot deactivate your own account.',
            ]);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return $user->refresh();
    }

    public function deleteUser(User $user, User $actor): void
    {
        if ($user->id === $actor->id) {
            throw ValidationException::withMessages([
                'delete' => 'You cannot delete your own account.',
            ]);
        }

        if (! $user->trashed()) {
            $user->delete();
        }
    }

    public function restoreUser(User $user): User
    {
        if ($user->trashed()) {
            $user->restore();
        }

        return $user->refresh();
    }

    public function findUserIncludingTrashed(int $id): User
    {
        return User::withTrashed()->findOrFail($id);
    }

    private function getUsers(array $filters): LengthAwarePaginator
    {
        $query = User::with(['role'])->withTrashed();

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $role = (string) ($filters['role'] ?? '');
        if ($role !== '') {
            $query->whereHas('role', fn ($builder) => $builder->where('slug', $role));
        }

        $status = (string) ($filters['status'] ?? '');
        if ($status === 'active') {
            $query->whereNull('deleted_at')->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->whereNull('deleted_at')->where('is_active', false);
        } elseif ($status === 'deleted') {
            $query->onlyTrashed();
        }

        $sort = (string) ($filters['sort'] ?? 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->paginate(15)->withQueryString();
    }

    private function getStats(): array
    {
        $base = User::query();

        return [
            'total_users' => (clone $base)->count(),
            'admins' => (clone $base)->whereHas('role', fn ($query) => $query->where('slug', 'admin'))->count(),
            'employers' => (clone $base)->whereHas('role', fn ($query) => $query->where('slug', 'employer'))->count(),
            'job_seekers' => (clone $base)->whereHas('role', fn ($query) => $query->where('slug', 'job-seeker'))->count(),
            'inactive_users' => (clone $base)->where('is_active', false)->count(),
        ];
    }

    private function ensureSelfProtection(User $user, array $data, User $actor): void
    {
        if ($user->id !== $actor->id) {
            return;
        }

        if (! (bool) $data['is_active']) {
            throw ValidationException::withMessages([
                'is_active' => 'You cannot deactivate your own account.',
            ]);
        }

        $newRole = Role::find((int) $data['role_id']);
        if ($newRole?->slug !== 'admin') {
            throw ValidationException::withMessages([
                'role_id' => 'You cannot remove your own admin role.',
            ]);
        }
    }

    private function ensureRoleChangeIsSafe(User $user, int $newRoleId): void
    {
        $newRole = Role::find($newRoleId);
        if (! $newRole) {
            return;
        }

        $newSlug = $newRole->slug;

        if ($user->company && $newSlug !== 'employer') {
            throw ValidationException::withMessages([
                'role_id' => 'Cannot change role because this user is linked to an employer company.',
            ]);
        }

        if ($user->jobSeekerProfile && $newSlug !== 'job-seeker') {
            throw ValidationException::withMessages([
                'role_id' => 'Cannot change role because this user has a job seeker profile.',
            ]);
        }
    }
}
