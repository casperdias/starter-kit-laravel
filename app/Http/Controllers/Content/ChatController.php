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

        request()->merge(['hide_content' => true]);

        $conversations = Conversation::with(['participants', 'lastMessage.user'])
            ->WhereHas('participants', function ($query) use ($me) {
                $query->where('id', $me->id);
            })->cursorPaginate(8);

        return Inertia::render('content/chat/Index', [
            'conversations' => Inertia::scroll(ConversationResource::collection($conversations)),
        ]);
    }

    public function store(ConversationRequest $request, StartConversation $startConversation)
    {
        $conversation = $startConversation->handle($request);

        return back()->with('message', $conversation->id);
    }
}
