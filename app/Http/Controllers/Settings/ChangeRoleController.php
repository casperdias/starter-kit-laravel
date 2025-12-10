<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\ChangeRole;
use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use Illuminate\Http\Request;

class ChangeRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ChangeRole $changeRole)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $user = $request->user();
        $role = Role::find($request->input('role'));

        if (! $changeRole->handle($user, $role)) {
            return back()->withErrors(['message' => __('You do not have this role.')]);
        }

        return back()
            ->with('success', __('Role changed successfully.'));
    }
}
