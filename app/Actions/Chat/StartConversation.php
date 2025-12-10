<?php

namespace App\Actions\Chat;

use App\Http\Requests\Content\ConversationRequest;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;

class StartConversation
{
    public function __construct(
        private CreatePrivateChat $createPrivate,
        private CreateGroupChat $createGroup,
    ) {
        //
    }

    public function create(ConversationRequest $request, User $me)
    {
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
