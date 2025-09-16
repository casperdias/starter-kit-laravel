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
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A news article has been published.',
            'action_url' => route('news.index'),
            'title' => $this->news->title,
            'type' => 'news',
        ];
    }
}
