<?php

namespace App\Http\Controllers;

use App\Events\ChatSent;
use App\Http\Requests\ChatRequest;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\ChatResource;
use App\Models\User\Chat;
use App\Models\User\User;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Chat');
    }

    public function get()
    {
        $chats = Chat::with('user', 'taggedUser')
            ->when(! auth()->user()->can('admin'), function ($query) {
                $query->where('tagged_id', auth()->id())
                    ->orWhere('user_id', auth()->id());
            })
            ->get();

        return ChatResource::collection($chats);
    }

    public function tagable()
    {
        $users = User::whereDoesntHave('activeRole.permissions', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return UserResource::collection($users);
    }

    public function send(ChatRequest $request)
    {
        // Handle the chat message sending logic
        $chat = Chat::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'tagged_id' => $request->tagged_id ?? null,
        ]);

        broadcast(new ChatSent($chat))->toOthers();

        return response()->json([
            'message' => 'Chat message sent successfully.',
            'id' => $chat->id,
        ]);
    }
}
