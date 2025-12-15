<?php

use App\Broadcasting\ConversationChannel;
use App\Broadcasting\NotificationChannel;
use App\Broadcasting\OnlineChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Auth.User.{id}', NotificationChannel::class);
Broadcast::channel('presence-online', OnlineChannel::class);
Broadcast::channel('conversation.{conversation}', ConversationChannel::class);
