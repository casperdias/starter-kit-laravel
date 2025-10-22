<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');

        return Inertia::render('content/chat/Index', [
            'search' => $search,
        ]);
    }
}
