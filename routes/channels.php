<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-chat', function (User $user) {
    return $user->can('admin');
});

Broadcast::channel('user-complaint.{id}', function (User $user, $id) {
    return $user->id === (int) $id;
});
