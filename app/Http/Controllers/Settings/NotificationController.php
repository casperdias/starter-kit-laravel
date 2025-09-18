<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setting\NotificationResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $page = request('page', 1);
        $per_page = request('per_page', 5);

        $user = request()->user();

        $notifications = $user->notifications()
            ->when($search, function ($query, $search) {
                return $query->where('data->type', 'like', "%{$search}%");
            })
            ->paginate($per_page, ['*'], 'page', $page);

        return Inertia::render('settings/Notification', [
            'notifications' => NotificationResource::collection($notifications),
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
}
