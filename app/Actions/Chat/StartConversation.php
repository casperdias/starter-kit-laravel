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
            return match ($request->type) {
                'private' => $this->createPrivate->handle($request, $me),
                'group' => $this->createGroup->handle($request, $me),
                default => throw new \InvalidArgumentException('Invalid conversation type: '.$request->type),
            };
        });
    }
}
