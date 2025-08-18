<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user-complain-chat', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name];
});
