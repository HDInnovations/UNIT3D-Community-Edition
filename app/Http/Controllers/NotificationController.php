<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications;
use \Toastr;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function get()
    {
        $notification = auth()->user()->notifications;
        return view('notification.notifications', ['notification' => $notification]);
    }

    public function read($id)
    {
        auth()->user()->unreadNotifications()->findOrFail($id)->markAsRead();
        return redirect()->route('get_notifications')->with(Toastr::success('Notification Marked As Read!', 'Yay!', ['options']));
    }

    public function massRead()
    {
        $current = new Carbon();
        auth()->user()->unreadNotifications()->update(['read_at' => $current]);
        return redirect()->route('get_notifications')->with(Toastr::success('All Notifications Marked As Read!', 'Yay!', ['options']));
    }

    public function delete($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return redirect()->route('get_notifications')->with(Toastr::success('Notification Deleted!', 'Yay!', ['options']));
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();
        return redirect()->route('get_notifications')->with(Toastr::success('All Notifications Deleted!', 'Yay!', ['options']));
    }
}
