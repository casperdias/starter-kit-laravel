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
     * Handle SSO login.
     */
    public function ssoLogin(Request $request)
    {
        // Implement SSO login logic here
        $request->session()->put('state', $state = Str::random(40));
        // Store the state in Cache (Backup if session is lost)
        cache()->put('sso_state_' . $state, $state, now()->addMinutes(5));
        $query = http_build_query([
            'client_id' => config('app.passport.client_id'),
            'redirect_uri' => config('app.passport.callback_path'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect($loginUrl = config('app.passport.login_url').'/oauth/authorize'.'?'.$query);
    }

    /**
     * Handle the SSO callback.
     */
    public function ssoCallback(Request $request)
    {
        $state = $request->session()->pull('state') ?? cache()->pull('sso_state_' . $request->state);

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

        // Get the user information from the response
        $user = Http::withToken($response->json('access_token'))
            ->get(config('app.passport.login_url').'/api/user')
            ->json();

        // Find or create the user in your local database from email, if not found redirect to login
        $localUser = User::where('email', $user['email'])->first();
        if (! $localUser) {
            return redirect()->route('login')->withErrors(['email' => 'User not found. Please register first.']);
        }

        // Log the user in
        Auth::login($localUser, true);

        // Redirect to intended route
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
