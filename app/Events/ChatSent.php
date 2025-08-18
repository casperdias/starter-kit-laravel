<?php

namespace App\Events;

use App\Models\User\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
        $channels = [
            new PrivateChannel('admin-chat'),
        ];

        if ($this->chat->tagged_id !== null) {
            $channels[] = new PrivateChannel('user-complain.'.$this->chat->tagged_id);
        }

        return $channels;
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
