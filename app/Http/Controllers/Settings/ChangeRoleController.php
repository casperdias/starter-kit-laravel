<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\ChangeRole;
use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChangeRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ChangeRole $changeRole): RedirectResponse
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $user = $request->user();
        $role = Role::where('id', $request->input('role'))->firstOrFail();

        if (! $changeRole->handle($user, $role)) {
            return back()->withErrors(['message' => __('You do not have this role.')]);
        }

        return back()
            ->with('success', __('Role changed successfully.'));
    }
}
