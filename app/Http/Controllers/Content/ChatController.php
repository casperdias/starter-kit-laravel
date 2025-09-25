<?php

namespace App\Http\Controllers\Content;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 5);
        $search = request('search', '');
        $users = User::where('id', '!=', auth()->id())
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->with('lastChat')
            ->cursorPaginate($perPage);

        return Inertia::render('content/chat/Index', [
            'users' => $users,
            'search' => $search,
        ]);
    }
}
