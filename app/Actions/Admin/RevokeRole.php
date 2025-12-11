<?php

namespace App\Actions\Admin;

use App\Models\Auth\Role;
use App\Models\Auth\User;

class RevokeRole
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

        if ($user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->detach($role);

            if ($user->roles()->where('status', 'ACTIVE')->doesntExist()) {
                $firstRole = $user->roles()->first();
                if ($firstRole) {
                    $user->roles()->updateExistingPivot($firstRole->id, ['status' => 'ACTIVE']);
                }
            }

            return true;
        }

        return false;
    }
}
