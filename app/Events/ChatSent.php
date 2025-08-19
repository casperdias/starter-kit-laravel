<?php

namespace App\Events;

use App\Http\Resources\ChatResource;
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
        $this->chat->load(['user', 'taggedUser']);
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
            $channels[] = new PrivateChannel('user-complaint.'.$this->chat->tagged_id);
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return new ChatResource($this->chat)->toArray(request());
    }
}
