<?php

namespace App\Listeners;

use App\Events\Content\News\NewsCreated;
use App\Models\Auth\User;
use App\Notifications\Content\News\NewsPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewsNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsCreated $event): void
    {
        $users = User::all();
        $news = $event->news;

        Notification::send($users, new NewsPublished($news));
    }
}
