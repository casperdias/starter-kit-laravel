<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-chat', function (User $user) {
    return $user->can('admin');
});

Broadcast::channel('user-complain.{id}', function (User $user, $id) {
    return $user->id === (int) $id;
});
