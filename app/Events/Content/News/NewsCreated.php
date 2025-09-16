<?php

namespace App\Events\Content\News;

use App\Models\Content\News;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PublicChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public News $news,
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
            new PublicChannel('push-notifications'),
        ];
    }
}
