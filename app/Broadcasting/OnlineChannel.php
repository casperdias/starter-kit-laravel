<?php

namespace App\Broadcasting;

use App\Models\Auth\User;

class OnlineChannel
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
     *
     * @return array<string, int|string>
     */
    public function join(User $user): array
    {
        return ['id' => $user->id, 'name' => $user->name];
    }
}
