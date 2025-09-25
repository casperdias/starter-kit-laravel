<?php

namespace App\Http\Controllers\Content;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $users = User::select('id', 'name', 'email')
            ->where('id', '!=', auth()->id())
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->cursorPaginate($perPage);

        return Inertia::render('content/chat/Index', [
            'users' => Inertia::deepMerge(UserResource::collection($users)),
            'search' => $search,
        ]);
    }
}
