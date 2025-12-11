<?php

namespace App\Actions\Admin;

use App\Http\Requests\Admin\UserRoleRequest;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;

class UpdateRole
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private AssignRole $assignRole,
        private RevokeRole $revokeRole
    ) {
        //
    }

    public function handle(UserRoleRequest $request, User $user, Role $role): bool
    {
        return DB::transaction(function () use ($request, $user, $role) {
            if ($request->status) {
                $status = $this->assignRole->handle($user, $role);
            } else {
                $status = $this->revokeRole->handle($user, $role);
            }

            return $status;
        });
    }
}
