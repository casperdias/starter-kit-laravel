<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Middleware;

class PassportRedirect extends Middleware
{
    /**
     * Handle the incoming request.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = parent::handle($request, $next);

        $location = $response->headers->get('Location');

        if (is_string($location) && ! Str::of($location)->startsWith(config('app.url'))) {
            return Inertia::location($location);
        }

        return $response;
    }
}
