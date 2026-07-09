<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $roles = Role::query()->orderBy('name')->get();

        return response()->json($roles, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Create role form placeholder'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json($role, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): JsonResponse
    {
        return response()->json($role, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role): JsonResponse
    {
        $role->update($request->validated());

        return response()->json($role->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json(null, 204);
    }
}
