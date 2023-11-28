<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function get(Request $request)
    {
        $page = $request->input('page', 1);

        $user = Auth::user();
        $notifications = $user
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'page', $page);

        $total_unread = $user
            ->notifications()
            ->where('read', false)
            ->count();
        return [
            'notifications' => $notifications->items(),
            'last_page' => $notifications->lastPage(),
            'current_page' => $notifications->currentPage(),
            'total' => $notifications->total(),
            'unread' => $total_unread,
        ];
    }
}
