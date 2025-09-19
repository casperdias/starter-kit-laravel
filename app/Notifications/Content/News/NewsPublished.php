<?php

namespace App\Notifications\Content\News;

use App\Models\Content\News;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsPublished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public News $news
    ) {
        $news->load('author');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A new news article has been published.',
            'title' => $this->news->title,
            'type' => 'news',
            'from' => $this->news->author->name,
        ];
    }
}
