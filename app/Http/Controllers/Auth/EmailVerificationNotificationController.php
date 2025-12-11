<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('email-verification', $request->user());

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', __('Verification Link Sent'));
    }

    public function storeCustom(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('email-verification', $user);

        if ($user->hasVerifiedEmail()) {
            return back()->with('message', __('Email already verified.'));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', __('Email Verification Sent'));
    }
}
