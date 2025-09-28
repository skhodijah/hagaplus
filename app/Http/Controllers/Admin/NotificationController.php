<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current user
     */
    public function index(Request $request)
    {
        // Handle AJAX request for dropdown
        if ($request->wantsJson()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => Notification::where('user_id', Auth::id())->unread()->count()
            ]);
        }

        // Handle page request for full view
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsUnread();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete all notifications
     */
    public function destroyAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}