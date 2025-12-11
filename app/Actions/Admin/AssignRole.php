<?php

namespace App\Actions\Admin;

use App\Models\Auth\Role;
use App\Models\Auth\User;

class AssignRole
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(User $user, Role $role)
    {
        $user->load('roles');

        if (! $user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role, ['status' => $user->roles->isEmpty() ? 'ACTIVE' : 'INACTIVE']);

            return true;
        }

        return false;
    }
}
