<?php

namespace App\Events;

use App\Models\User\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Chat $chat
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user-complain-chat'),
        ];
    }

    public function broadcastWhen(): bool
    {
        return auth()->user()->can('admin') ||
            auth()->id() === $this->chat->tagged_id;
        // return true; // Always broadcast to all listeners on the channel
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->chat->id,
            'message' => $this->chat->message,
            'user' => $this->chat->user,
            'taggedUser' => $this->chat->taggedUser ?? null,
            'created_at' => $this->chat->created_at->toDateTimeString(),
        ];
    }
}
