<?php

namespace App\Http\Controllers;

use App\Models\User\Chat;
use App\Http\Requests\ChatRequest;
use Inertia\Inertia;
use App\Events\ChatSent;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Chat');
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
