<?php

namespace App\Actions\Chat;

use App\Http\Requests\Content\ConversationRequest;
use App\Models\Content\Conversation;
use Illuminate\Support\Facades\DB;

class StartConversation
{
    public function __construct(
        private CreatePrivateChat $createPrivate,
        private CreateGroupChat $createGroup,
    ) {
        //
    }

    public function handle(ConversationRequest $request): Conversation
    {
        $me = $request->user();

        return DB::transaction(function () use ($request, $me) {
            if ($request->type === 'private') {
                $conversation = $this->createPrivate->handle($request, $me);
            } elseif ($request->type === 'group') {
                $conversation = $this->createGroup->handle($request, $me);
            }

            return $conversation;
        });
    }
}
