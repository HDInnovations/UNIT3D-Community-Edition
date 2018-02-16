<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Notifications;
use \Toastr;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function get()
    {
        $notification = Auth::user()->notifications;
        return view('notification.notifications', ['notification' => $notification]);
    }

    public function read($id)
    {
        Auth::user()->unreadNotifications()->findOrFail($id)->markAsRead();
        return Redirect::route('get_notifications')->with(Toastr::success('Notification Marked As Read!', 'Yay!', ['options']));
    }

    public function massRead()
    {
        $current = new Carbon();
        Auth::user()->unreadNotifications()->update(['read_at' => $current]);
        return Redirect::route('get_notifications')->with(Toastr::success('All Notifications Marked As Read!', 'Yay!', ['options']));
    }

    public function delete($id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
        return Redirect::route('get_notifications')->with(Toastr::success('Notification Deleted!', 'Yay!', ['options']));
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        return Redirect::route('get_notifications')->with(Toastr::success('All Notifications Deleted!', 'Yay!', ['options']));
    }
}
