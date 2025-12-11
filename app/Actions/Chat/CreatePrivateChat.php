<?php

namespace App\Actions\Chat;

use App\Http\Requests\Content\ConversationRequest;
use App\Models\Auth\User;
use App\Models\Content\Conversation;

class CreatePrivateChat
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(ConversationRequest $request, User $me): Conversation
    {
        $user1 = $me->id;
        $user2 = $request->user_id;

        $conversation = Conversation::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user1) {
                $query->where('user_id', $user1);
            })
            ->whereHas('participants', function ($query) use ($user2) {
                $query->where('user_id', $user2);
            })
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'name' => 'Private '.$user1.'-'.$user2,
                'type' => 'private',
                'created_by' => $user1,
            ]);

            $conversation->participants()->attach([
                $user1 => [
                    'joined_at' => now(),
                    'role' => 'member',
                ],
                $user2 => [
                    'joined_at' => now(),
                    'role' => 'member',
                ],
            ]);
        }

        return $conversation;
    }
}
