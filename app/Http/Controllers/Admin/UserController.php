<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\UpdateRole;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\Admin\UserRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $search = request('search', '');
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $users = User::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('admin/user/Index', [
            'users' => UserResource::collection($users),
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
    public function store(UserRequest $request, CreateNewUser $creator): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = $creator->create($request->all());

        event(new Registered($user));

        return back()
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        Gate::authorize('view', $user);

        $user->load('roles');

        $search = request('search', '');
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $roles = Role::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('admin/user/Show', [
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Update the user's role.
     */
    public function updateRole(UserRoleRequest $request, User $user, Role $role, UpdateRole $updateRole): RedirectResponse
    {
        Gate::authorize('update', $user);

        $updateRole->handle($request, $user, $role);

        return back()
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): JsonResponse
    {
        Gate::authorize('update', $user);

        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->update($request->validated());

        return back()
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => __('You cannot delete your own account.')]);
        }

        if ($user->id === 1) {
            return back()->withErrors(['message' => __('You cannot delete a super admin user.')]);
        }

        $user->delete();

        return back()
            ->with('success', 'User deleted successfully.');
    }
}
