<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function userList(Request $request)
    {
        $search = request('search', '');

        $users = User::select('id', 'name', 'email')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->simplePaginate(5);

        return response()->json([
            'users' => UserResource::collection($users),
            'hasMore' => $users->hasMorePages(),
        ]);
    }
}
