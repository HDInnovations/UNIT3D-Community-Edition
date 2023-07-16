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
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\NotificationControllerTest
 */
class NotificationController extends Controller
{
    /**
     * Show All Notifications.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.notification.index');
    }

    /**
     * Show A Notification And Mark As Read.
     */
    public function show(Request $request, User $user, DatabaseNotification $notification): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $notification->markAsRead();

        return redirect()->to($notification->data['url'])
            ->withSuccess(trans('notification.marked-read'));
    }

    /**
     * Set A Notification To Read.
     */
    public function update(Request $request, User $user, DatabaseNotification $notification): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $notification->markAsRead();

        return to_route('users.notifications.index', ['user' => $user])
            ->withSuccess(trans('notification.marked-read'));
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @throws Exception
     */
    public function massUpdate(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return to_route('users.notifications.index', ['user' => $user])
            ->withSuccess(trans('notification.all-marked-read'));
    }

    /**
     * Delete A Notification.
     */
    public function destroy(Request $request, User $user, DatabaseNotification $notification): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $notification->delete();

        return to_route('users.notifications.index', ['user' => $user])
            ->withSuccess(trans('notification.deleted'));
    }

    /**
     * Mass Delete All Notification's.
     */
    public function massDestroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $user->notifications()->delete();

        return to_route('users.notifications.index', ['user' => $user])
            ->withSuccess(trans('notification.all-deleted'));
    }
}
