<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-chat', function (User $user) {
    if ($user->can('admin')) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'is_admin' => $user->can('admin'),
        ];
    }
});

Broadcast::channel('user-complaint.{id}', function (User $user, $id) {
    if ($user->can('admin') || $user->id === (int) $id) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'is_admin' => $user->can('admin'),
        ];
    }
});
