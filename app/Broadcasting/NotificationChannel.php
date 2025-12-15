<?php

namespace App\Broadcasting;

use App\Models\Auth\User;

class NotificationChannel
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
    public function join(User $user, int $id): bool
    {
        return (int) $user->id === (int) $id;
    }
}
