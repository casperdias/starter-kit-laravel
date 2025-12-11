<?php

namespace App\Actions\Admin;

use App\Http\Requests\Admin\RolePermissionRequest;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\DB;

class UpdatePermission
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private AssignPermission $assignPermission,
        private RevokePermission $revokePermission
    ) {
        //
    }

    public function handle(RolePermissionRequest $request, Role $role, Permission $permission)
    {
        return DB::transaction(function () use ($request, $role, $permission) {
            if ($request->status) {
                $status = $this->assignPermission->handle($role, $permission);
            } else {
                $status = $this->revokePermission->handle($role, $permission);
            }

            return $status;
        });
    }
}
