<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Return recent notifications for the authenticated user (JSON).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['items' => [], 'unread' => 0]);
            }
            return redirect()->route('login');
        }

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        if (! $request->wantsJson()) {
            Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $items = $notifications->map(function ($notification) {
            $data = $notification->data ?? [];
            $message = 'Anda menerima notifikasi baru.';
            $url = url('/dashboard');

            if ($notification->type === 'comment') {
                $commenter = $data['commenter_name'] ?? 'Seseorang';
                $postTitle = $data['post_title'] ?? 'postingannya';
                $message = sprintf('%s mengomentari postingan Anda: %s', $commenter, $postTitle);
                $url = url('/posts/' . ($data['post_id'] ?? ''));
            }

            return [
                'id' => $notification->id,
                'message' => $message,
                'url' => $url,
                'time' => $notification->created_at->diffForHumans(),
                'unread' => $notification->read_at === null,
            ];
        });

        $unreadCount = $items->where('unread', true)->count();

        if ($request->wantsJson()) {
            return response()->json(['items' => $items->toArray(), 'unread' => $unreadCount]);
        }

        return view('notifications.index', [
            'notifications' => $items,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark provided notification ids as read.
     */
    public function markRead(Request $request)
    {
        $ids = $request->input('ids', []);
        if (! is_array($ids)) {
            $ids = [$ids];
        }

        if (empty($ids)) {
            return response()->json(['ok' => true]);
        }

        Notification::where('user_id', Auth::id())
            ->whereIn('id', $ids)
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
