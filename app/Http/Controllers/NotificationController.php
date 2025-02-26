<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return response()->json($notifications);
    }

    public function clearNotifications()
    {
        auth()->user()->notifications()->delete();
        return redirect()->back();
    }
}
