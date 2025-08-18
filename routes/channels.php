<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

// Channel for chat to Admin Feature
Broadcast::channel('user-complain-chat', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name];
});
