<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $Admin = Admin::find(auth()->user()->id);

        foreach ($Admin->notifications as $notification) {
            return $notification;
        }
    }
    public function show($id)
    {
        $Admin = Admin::find(auth()->user()->id);
        if ($Admin) {
            $notification = DB::table('notifications')->where('notifiable_id', $id)->get('data');
            if ($notification) {
                return response()->json(['notification' => $notification]);
            }
            return response()->json(['notification' => $notification]);
            }
            return response()->json(['error' => 'no such admin']);
    }
    
    
    public function unreadAll()
    {
        $Admin = Admin::find(auth()->user()->id);
        return response()->json(['notification' => $Admin->unreadNotifications]);
    }
    // public function unreadAll()
    // {
    //     $Admin = Admin::find(auth()->user()->id);
    //     return response()->json(['notification' => $Admin->notifications]);
    // }
    public function markAsRead()
    {
        $Admin = Admin::find(auth()->user()->id);
        $ReadNotification = $Admin->unreadNotifications->markAsRead();
        return response()->json(['message' => 'notification has been read ', 'notification' => $ReadNotification]);
    }
    public function markAsReadAll()
    {
        $Admins = Admin::get();

        foreach ($Admins as $admin) {
            $unreadNotifications = $admin->unreadNotifications;
            $ReadNotification = $unreadNotifications->markAsRead();
        }
        return response()->json(['notification' => $ReadNotification]);
    }
    public function delete($id)
    {
        $Admin = Admin::find($id);
        if ($Admin) {
            return response()->json(['message' => 'notification deleted successfully', 'notification' => $Admin->notifications()->delete(),
            ]);
        }
        return response()->json(['error' => 'no such admin']);

    }
    public function deleteAll()
    {
        $Admins = Admin::get();

        foreach ($Admins as $admin) {
            $admin->notifications()->delete();
        }
        return response()->json(['message' => 'notifications deleted successfully']);

    }
}
