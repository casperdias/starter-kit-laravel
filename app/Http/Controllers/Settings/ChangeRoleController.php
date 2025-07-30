<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User\Role;
use Illuminate\Http\Request;

class ChangeRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $user = $request->user();
        $role = Role::findOrFail($request->input('role'));
        $user->changeRole($role);

        return back()
            ->with('success', 'Role changed successfully.');
    }
}
