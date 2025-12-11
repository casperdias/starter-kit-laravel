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

    public function handle(User $user, Role $role): bool
    {
        $detached = $user->roles()->detach($role);

        if ($detached > 0) {
            $hasActiveRole = $user->roles()->wherePivot('status', 'ACTIVE')->exists();

            if (! $hasActiveRole) {
                $firstRoleId = $user->roles()->value('id');

                if ($firstRoleId) {
                    $user->roles()->updateExistingPivot($firstRoleId, ['status' => 'ACTIVE']);
                }
            }

            return true;
        }

        return false;
    }
}
