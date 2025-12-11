<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\UpdatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolePermissionRequest;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        $search = request('search', '');
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $roles = Role::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('admin/role/Index', [
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $role = Role::create($request->validated());

        return back()
            ->with('success', __('Role created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): Response
    {
        Gate::authorize('view', $role);

        $role->load('permissions');

        $search = request('search', '');
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $permissions = Permission::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('admin/role/Show', [
            'role' => new RoleResource($role),
            'permissions' => PermissionResource::collection($permissions),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): JsonResponse
    {
        Gate::authorize('update', $role);

        return response()->json(new RoleResource($role));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $role->update($request->validated());

        return back()
            ->with('success', __('Role updated successfully.'));
    }

    public function updatePermission(RolePermissionRequest $request, Role $role, Permission $permission, UpdatePermission $updatePermission): RedirectResponse
    {
        Gate::authorize('update', $role);

        $updatePermission->handle($request, $role, $permission);

        return back()
            ->with('success', __('Role permissions updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('delete', $role);

        if ($role->id === 1) {
            return back()->withErrors(['message' => __('This role cannot be deleted.')]);
        }

        // If the role is associated with any users, do not allow deletion
        if ($role->users()->exists()) {
            return back()->withErrors(['message' => __('This role cannot be deleted because it is associated with one or more users.')]);
        }

        $role->delete();

        return back()
            ->with('success', __('Role deleted successfully.'));
    }
}
