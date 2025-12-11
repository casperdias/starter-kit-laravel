<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        if (Gate::any(['user', 'role', 'permission'])) {
            return Inertia::render('admin/Home');
        }

        abort(403);
    }
}
