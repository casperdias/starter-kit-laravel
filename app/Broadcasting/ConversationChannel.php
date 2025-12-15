<?php

namespace App\Broadcasting;

use App\Models\Auth\User;
use App\Models\Content\Conversation;

class ConversationChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, Conversation $conversation): User
    {
        return $conversation->isParticipant($user->id);
    }
}
