<?php

namespace App\Actions\Admin;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;

class AssignPermission
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(Role $role, Permission $permission): bool
    {
        $role->load('permissions');

        if (! $role->permissions()->where('permission_id', $permission->id)->exists()) {
            $role->permissions()->attach($permission);

            return true;
        }

        return false;
    }
}
