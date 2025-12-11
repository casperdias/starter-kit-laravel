<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\UserResource;
use App\Models\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExternalController extends Controller
{
    public function userList(Request $request): JsonResponse
    {
        $referer = $request->headers->get('referer');
        if (! $referer || ! Str::contains($referer, [
            route('chats.index'),
        ])) {
            abort(403, 'Access denied');
        }

        $search = $request->search ?? null;

        $users = User::select('id', 'name', 'email')
            ->whereNot('id', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($search).'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.strtolower($search).'%']);
            })
            ->simplePaginate(5);

        return response()->json([
            'users' => UserResource::collection($users),
            'hasMore' => $users->hasMorePages(),
        ]);
    }
}
