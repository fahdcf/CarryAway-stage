<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->with('request')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $user = Auth::user();
        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue',
            'unread_count' => $user->unreadNotificationsCount()
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        $user->markAllNotificationsAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues',
            'unread_count' => 0
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $user = Auth::user();
        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée',
            'unread_count' => $user->unreadNotificationsCount()
        ]);
    }

    /**
     * Get unread notifications count for AJAX requests
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'unread_count' => $user->unreadNotificationsCount()
        ]);
    }

    /**
     * Get latest notifications for AJAX requests
     */
    public function getLatest(): JsonResponse
    {
        $user = Auth::user();
        $notifications = $user->latestNotifications(5);
        
        return response()->json([
            'notifications' => $notifications
        ]);
    }
}
