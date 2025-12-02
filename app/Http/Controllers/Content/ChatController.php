<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ConversationRequest;
use App\Http\Resources\Content\ConversationResource;
use App\Models\Content\Conversation;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $me = auth()->user();

        $conversations = Conversation::with('participants')
            ->WhereHas('participants', function ($query) use ($me) {
                $query->where('id', $me->id);
            })->cursorPaginate(8);

        return Inertia::render('content/chat/Index', [
            'search' => $search,
            'conversations' => ConversationResource::collection($conversations),
        ]);
    }

    public function store(ConversationRequest $request)
    {
        $me = auth()->user();

        if ($request->type === 'private') {
            $conversation = Conversation::findOrCreatePrivate($me->id, $request->user_id);

            return response()->json([
                'conversation_id' => $conversation->id,
            ], 201);
        } else {
            abort(404);
        }

        abort(404);
    }
}
