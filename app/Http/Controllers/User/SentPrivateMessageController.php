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
use Illuminate\Validation\Rule;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PrivateMessageControllerTest
 */
class SentPrivateMessageController extends Controller
{
    /**
     * View/Search PM Outbox.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.sent-private-message.index', [
            'user' => $user,
            'pms'  => $user
                ->sentPrivateMessages()
                ->with('receiver.group')
                ->select('id', 'receiver_id', 'subject', 'created_at')
                ->when(
                    $request->has('subject'),
                    fn ($query) => $query->where('subject', 'like', '%'.$request->string('subject').'%')
                )
                ->latest()
                ->paginate(25)
                ->withQueryString(),
            'subject' => $request->subject ?? '',
        ]);
    }

    /**
     * View A Message.
     */
    public function show(Request $request, User $user, PrivateMessage $sentPrivateMessage): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.sent-private-message.show', [
            'privateMessage' => $sentPrivateMessage,
            'user'           => $user
        ]);
    }

    /**
     * Create Message Form.
     */
    public function create(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.sent-private-message.create', [
            'user'     => $user,
            'username' => $request->query('username'),
        ]);
    }

    /**
     * Create A Message.
     */
    public function store(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $request->validate([
            'subject' => [
                'required',
                'max:255',
            ],
            'message' => [
                'required',
                'max:65536',
            ],
            'receiver_username' => [
                Rule::exists('users', 'username')->whereNot('username', $user->username),
            ]
        ]);

        $recipient = User::where('username', '=', $request->string('receiver_username'))->sole();

        PrivateMessage::create([
            'sender_id'   => $user->id,
            'receiver_id' => $recipient->id,
            'subject'     => $request->string('subject'),
            'message'     => $request->string('message'),
            'read'        => 0,
        ]);

        return to_route('users.sent_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.sent-success'));
    }

    /**
     * Reply To A Message.
     */
    public function update(Request $request, User $user, PrivateMessage $sentPrivateMessage): \Illuminate\Http\RedirectResponse
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
            'receiver_id' => $sentPrivateMessage->receiver_id,
            'subject'     => $sentPrivateMessage->subject,
            'message'     => $request->string('message'),
            'related_to'  => $sentPrivateMessage->id,
            'read'        => 0,
        ]);

        return to_route('users.sent_messages.index', ['user' => $user])
            ->withSuccess(trans('pm.sent-success'));
    }
}
