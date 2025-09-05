<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function storeCustom(Request $request, User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('message', 'Email already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email Verifikasi Terkirim');
    }
}
