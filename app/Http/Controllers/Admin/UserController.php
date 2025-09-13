<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\Admin\UserRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $search = request('search', '');
        $page = request('page', 1);
        $per_page = request('per_page', 5);

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        return Inertia::render('admin/user/Index', [
            'users' => UserResource::collection($users),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        Gate::authorize('create', User::class);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt('password'),
        ]);

        return back()
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        $user->load('roles');

        $search = request('search', '');
        $page = request('page', 1);
        $per_page = request('per_page', 5);

        $roles = Role::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        return Inertia::render('admin/user/Show', [
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles),
            'search' => $search,
        ]);
    }

    /**
     * Update the user's role.
     */
    public function updateRole(UserRoleRequest $request, User $user, Role $role)
    {
        Gate::authorize('update', $user);

        if ($request->status) {
            $user->assignRole($role);
        } else {
            $user->revokeRole($role);
        }

        return back()
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $user->update($request->validated());

        return back()
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => 'You cannot delete your own account.']);
        }

        if ($user->id === 1) {
            return back()->withErrors(['message' => 'You cannot delete a super admin user.']);
        }

        $user->delete();

        return back()
            ->with('success', 'User deleted successfully.');
    }
}
