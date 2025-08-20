<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-chat', function (User $user) {
    return $user->can('admin') ? [
        'id' => $user->id,
        'name' => $user->name,
        'is_admin' => $user->can('admin'),
    ] : false;
});

Broadcast::channel('user-complaint.{id}', function (User $user, $id) {
    return $user->can('admin') || $user->id === (int) $id ? [
        'id' => $user->id,
        'name' => $user->name,
        'is_admin' => $user->can('admin'),
    ] : false;
});
