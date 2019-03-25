<?php

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Show All Notifications.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $notification = auth()->user()->notifications()->paginate(25);

        return view('notification.notifications', ['notification' => $notification]);
    }

    /**
     * Show A Notification And Mark As Read.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'])
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Set A Notification To Read.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function read($id)
    {
        auth()->user()->unreadNotifications()->findOrFail($id)->markAsRead();

        return redirect()->route('get_notifications')
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function massRead()
    {
        $current = new Carbon();
        auth()->user()->unreadNotifications()->update(['read_at' => $current]);

        return redirect()->route('get_notifications')
            ->withSuccess('All Notifications Marked As Read!');
    }

    /**
     * Delete A Notification.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return redirect()->route('get_notifications')
            ->withSuccess('Notification Deleted!');
    }

    /**
     * Mass Delete All Notification's.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('get_notifications')
            ->withSuccess('All Notifications Deleted!');
    }
}
