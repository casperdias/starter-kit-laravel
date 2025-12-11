<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Auth\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Permission::class);

        $search = request('search', '');
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $permissions = Permission::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('admin/permission/Index', [
            'permissions' => PermissionResource::collection($permissions),
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
    public function store(PermissionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Permission::class);

        $permission = Permission::create($request->validated());

        return back()
            ->with('success', __('Permission created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): JsonResponse
    {
        Gate::authorize('view', $permission);

        return response()->json(new PermissionResource($permission));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): JsonResponse
    {
        Gate::authorize('update', $permission);

        return response()->json(new PermissionResource($permission));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        Gate::authorize('update', $permission);

        $permission->update($request->validated());

        return back()
            ->with('success', __('Permission updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        Gate::authorize('delete', $permission);

        if ($permission->id === 1) {
            return back()->withErrors(['message' => __('This permission cannot be deleted.')]);
        }

        $permission->delete();

        return back()->with('success', __('Permission deleted successfully.'));
    }
}
