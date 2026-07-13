<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(private readonly AdminUserService $adminUserService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $filters = [
            'search' => $request->string('search')->toString(),
            'role' => $request->string('role')->toString(),
            'status' => $request->string('status')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];

        $data = $this->adminUserService->getListingData($filters);

        return view('admin.users.index', [
            'users' => $data['users'],
            'roles' => $data['roles'],
            'stats' => $data['stats'],
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $roles = $this->adminUserService->getAvailableRoles();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $this->adminUserService->createUser($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $roles = $this->adminUserService->getAvailableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $this->adminUserService->updateUser($user, $request->validated(), $request->user());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $this->adminUserService->toggleStatus($user, $request->user());

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $this->adminUserService->deleteUser($user, $request->user());

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function restore(Request $request, int $userId): RedirectResponse
    {
        $user = $this->adminUserService->findUserIncludingTrashed($userId);
        $this->authorize('restore', $user);

        $this->adminUserService->restoreUser($user);

        return redirect()->route('admin.users.index')->with('success', 'User restored successfully.');
    }
}
