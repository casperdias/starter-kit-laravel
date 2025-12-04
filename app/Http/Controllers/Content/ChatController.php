<?php

namespace App\Http\Controllers\Content;

use App\Actions\Chat\StartConversation;
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
            'conversations' => ConversationResource::collection($conversations),
        ]);
    }

    public function store(ConversationRequest $request, StartConversation $startConversation)
    {
        $me = auth()->user();

        return $startConversation->create($request, $me);
    }
}
