<?php

namespace App\Actions\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;

class ChangeRole
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
            return false;
        }

        $user->roles()->newPivotQuery()->where('user_id', $user->id)
            ->update(['status' => 'INACTIVE']);

        $user->roles()->updateExistingPivot($role->id, ['status' => 'ACTIVE']);

        return true;
    }
}
