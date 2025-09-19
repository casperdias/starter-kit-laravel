<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setting\NotificationResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected function getPaginatedNotifications(Authenticatable $user, int $page, int $perPage, bool $showUnread = false): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $user->notifications()
            ->when($showUnread, function ($query) {
                return $query->whereNull('read_at');
            })
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 5);

        $user = $request->user();

        $notifications = $this->getPaginatedNotifications($user, $page, $per_page);

        return Inertia::render('settings/Notification', [
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function dropdown(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 5);
        $show_unread = $request->boolean('show_unread', false);

        $user = $request->user();
        $unread = $user->unreadNotifications()->count();

        $notifications = $this->getPaginatedNotifications($user, $page, $per_page, $show_unread);

        return response()->json([
            'notifications' => NotificationResource::collection($notifications),
            'unread' => $unread,
            'more' => $notifications->hasMorePages(),
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', __('All notifications marked as read.'));
    }

    public function markAsRead(Request $request)
    {
        $user = $request->user();

        $notification = $user->unreadNotifications()->whereIn('id', $request->ids)->get();

        if ($notification->isNotEmpty()) {
            $notification->markAsRead();

            return back()->with('success', __('Selected notifications marked as read.'));
        }

        return back()->withErrors(['message' => __('Notification not found.')]);
    }

    public function markOneAsRead(Request $request, $notificationId)
    {
        $user = $request->user();

        $notification = $user->unreadNotifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();

            return back()->with('success', __('Notification marked as read.'));
        }

        return back()->withErrors(['message' => __('Notification not found.')]);
    }
}
