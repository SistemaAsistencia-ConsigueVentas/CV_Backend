<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications() {
        $notifications = Notifications::all();
        return response()->json($notifications);
    } 
}
