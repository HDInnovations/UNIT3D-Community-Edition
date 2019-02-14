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
use Brian2694\Toastr\Toastr;

class NotificationController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * NotificationController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Show All Notifications.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get(Request $request)
    {
        $notification = $request->user()->notifications()->paginate(25);

        return view('notification.notifications', ['notification' => $notification]);
    }

    /**
     * Show A Notification And Mark As Read.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'])->with($this->toastr->success('Notification Marked As Read!', 'Yay!', ['options']));
    }

    /**
     * Set A Notification To Read.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function read(Request $request, $id)
    {
        $request->user()->unreadNotifications()->findOrFail($id)->markAsRead();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('Notification Marked As Read!', 'Yay!', ['options']));
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function massRead(Request $request)
    {
        $current = new Carbon();
        $request->user()->unreadNotifications()->update(['read_at' => $current]);

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('All Notifications Marked As Read!', 'Yay!', ['options']));
    }

    /**
     * Delete A Notification.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('Notification Deleted!', 'Yay!', ['options']));
    }

    /**
     * Mass Delete All Notification's.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('All Notifications Deleted!', 'Yay!', ['options']));
    }
}
