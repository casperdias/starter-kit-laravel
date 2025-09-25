<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Auth.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('presence-online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
