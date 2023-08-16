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
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PrivateMessageControllerTest
 */
class ReceivedPrivateMessageController extends Controller
{
    /**
     * View/Search PM Inbox.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.received-private-message.index', [
            'user' => $user,
            'pms'  => $user
                ->receivedPrivateMessages()
                ->select('id', 'sender_id', 'subject', 'read', 'created_at')
                ->with('sender.group')
                ->when(
                    $request->has('subject'),
                    fn ($query) => $query->where('subject', 'like', '%'.$request->string('subject').'%')
                )
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'subject' => $request->subject ?? '',
        ]);
    }

    /**
     * View A Message.
     */
    public function show(Request $request, User $user, PrivateMessage $receivedPrivateMessage): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $receivedPrivateMessage->update([
            'read' => 1,
        ]);

        return view('user.received-private-message.show', [
            'pm'   => $receivedPrivateMessage,
            'user' => $user
        ]);
    }

    /**
     * Reply To A Message.
     */
    public function update(Request $request, User $user, PrivateMessage $receivedPrivateMessage): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $request->validate([
            'message' => [
                'required',
                'max:65536',
            ],
        ]);

        PrivateMessage::create([
            'sender_id'   => $user->id,
            'receiver_id' => $receivedPrivateMessage->sender_id,
            'subject'     => $receivedPrivateMessage->subject,
            'message'     => $request->string('message'),
            'related_to'  => $receivedPrivateMessage->id,
            'read'        => 0,
        ]);

        return to_route('users.received_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.sent-success'));
    }

    /**
     * Delete A Message.
     *
     * @throws Exception
     */
    public function destroy(Request $request, User $user, PrivateMessage $receivedPrivateMessage): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $receivedPrivateMessage->delete();

        return to_route('users.received_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.delete-success'));
    }

    /**
     * Empty Private Message Inbox.
     */
    public function massDestroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        PrivateMessage::where('receiver_id', '=', $user->id)->delete();

        return to_route('users.received_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.delete-success'));
    }

    /**
     * Mark All Messages As Read.
     */
    public function massUpdate(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        PrivateMessage::where('receiver_id', '=', $user->id)->update([
            'read' => true,
        ]);

        return to_route('users.received_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.all-marked-read'));
    }
}
