<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\NotificationControllerTest
 */
class NotificationController extends Controller
{
    /**
     * Show All Notifications.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('user.notification.index');
    }

    /**
     * Show A Notification And Mark As Read.
     */
    public function show(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return \redirect()->to($notification->data['url'])
            ->withSuccess(\trans('notification.marked-read'));
    }

    /**
     * Set A Notification To Read.
     */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', '=', $id)->first();

        if (! $notification) {
            return \to_route('notifications.index')
                ->withErrors(\trans('notification.not-existent'));
        }

        if ($notification->read_at != null) {
            return \to_route('notifications.index')
                ->withErrors(\trans('notification.already-marked-read'));
        }

        $notification->markAsRead();

        return \to_route('notifications.index')
            ->withSuccess(\trans('notification.marked-read'));
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @throws \Exception
     */
    public function updateAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        $carbon = new Carbon();
        $request->user()->unreadNotifications()->update(['read_at' => $carbon]);

        return \to_route('notifications.index')
            ->withSuccess(\trans('notification.all-marked-read'));
    }

    /**
     * Delete A Notification.
     */
    public function destroy(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return \to_route('notifications.index')
            ->withSuccess(\trans('notification.deleted'));
    }

    /**
     * Mass Delete All Notification's.
     */
    public function destroyAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->user()->notifications()->delete();

        return \to_route('notifications.index')
            ->withSuccess(\trans('notification.all-deleted'));
    }
}
