<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\BulkNotificationLog;
use App\Models\Core\User;
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
        if ($request->ajax() || $request->wantsJson() || $request->query('ajax')) {
            $notifications = Notification::where('user_id', Auth::id())
                ->unread()
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

        return view('superadmin.notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notification)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($notification);

        $notification->markAsRead();

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
    public function destroy($notification)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($notification);

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

    /**
     * Show bulk notification form
     */
    public function bulk()
    {
        $logs = BulkNotificationLog::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.notifications.bulk', compact('logs'));
    }

    /**
     * Send bulk notifications
     */
    public function sendBulk(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error,system',
            'target_type' => 'required|in:all_admins,specific_admins,all_employees,specific_employees',
            'admin_ids' => 'required_if:target_type,specific_admins|array',
            'admin_ids.*' => 'exists:users,id',
            'user_ids' => 'required_if:target_type,specific_employees|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = collect();

        switch ($request->target_type) {
            case 'all_admins':
                $users = User::where('role', 'admin')->get();
                break;
            case 'specific_admins':
                $users = User::whereIn('id', $request->admin_ids)
                    ->where('role', 'admin')
                    ->get();
                break;
            case 'all_employees':
                $users = User::where('role', 'employee')->get();
                break;
            case 'specific_employees':
                $users = User::whereIn('id', $request->user_ids)->get();
                break;
        }

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'is_read' => false,
            ]);
        }

        // Log the bulk notification
        BulkNotificationLog::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target_type' => $request->target_type,
            'target_ids' => match ($request->target_type) {
                'all_admins', 'all_employees' => null,
                'specific_admins' => $request->admin_ids,
                'specific_employees' => $request->user_ids,
            },
            'total_sent' => $users->count(),
            'sent_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Bulk notifications sent successfully to ' . $users->count() . ' users.');
    }


    /**
     * Get employees for bulk notification targeting
     */
    public function getEmployees()
    {
        $employees = User::where('role', 'employee')
            ->with('instansi')
            ->select('id', 'name', 'instansi_id')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'instansi_name' => $user->instansi ? $user->instansi->nama_instansi : 'N/A'
                ];
            });

        return response()->json(['employees' => $employees]);
    }
}
