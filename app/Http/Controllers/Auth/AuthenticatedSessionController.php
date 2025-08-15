<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle Oauth login.
     */
    public function oauthLogin(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => config('app.passport.client_id'),
            'redirect_uri' => config('app.passport.callback_path'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect(config('app.passport.login_url').'/oauth/authorize'.'?'.$query);
    }

    /**
     * Handle the Oauth callback.
     */
    public function oauthCallback(Request $request)
    {
        if ($request->error) {
            return redirect()->route('login')->withErrors(['email' => 'Oauth login failed: '.$request->error]);
        }

        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class,
            'Invalid state value.'
        );

        $response = Http::asForm()->post(config('app.passport.login_url').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('app.passport.client_id'),
            'client_secret' => config('app.passport.client_secret'),
            'redirect_uri' => config('app.passport.callback_path'),
            'code' => $request->code,
        ]);

        $user = Http::withToken($response->json('access_token'))
            ->get(config('app.passport.login_url').'/api/user')
            ->json();

        $localUser = User::where('email', $user['email'])->first();
        if (! $localUser) {
            return redirect()->route('login')->withErrors(['email' => 'User not found. Please register first.']);
        }

        Auth::login($localUser, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
