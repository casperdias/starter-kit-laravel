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

    public function handle(User $user, Role $role): bool
    {
        if (! $user->roles()->where('role_id', $role->id)->exists()) {
            $hasActiveRole = $user->roles()->wherePivot('status', 'ACTIVE')->exists();

            $user->roles()->attach($role, [
                'status' => $hasActiveRole ? 'INACTIVE' : 'ACTIVE',
            ]);

            return true;
        }

        return false;
    }
}
