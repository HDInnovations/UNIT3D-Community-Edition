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

namespace App\Http\Controllers;

use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Http\Request;

class PrivateMessageController extends Controller
{
    /**
     * Search PM Inbox.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchPMInbox(Request $request)
    {
        $user = $request->user();
        $pms = PrivateMessage::where('receiver_id', '=', $user->id)->where([
            ['subject', 'like', '%'.$request->input('subject').'%'],
        ])->latest()->paginate(20);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * Search PM Outbox.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchPMOutbox(Request $request)
    {
        $user = $request->user();
        $pms = PrivateMessage::where('sender_id', '=', $user->id)->where([
            ['subject', 'like', '%'.$request->input('subject').'%'],
        ])->latest()->paginate(20);

        return view('pm.outbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View Inbox.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessages(Request $request)
    {
        $user = $request->user();
        $pms = PrivateMessage::where('receiver_id', '=', $user->id)->latest()->paginate(25);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View Outbox.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessagesSent(Request $request)
    {
        $user = $request->user();
        $pms = PrivateMessage::where('sender_id', '=', $user->id)->latest()->paginate(20);

        return view('pm.outbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View A Message.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessageById(Request $request, $id)
    {
        $user = $request->user();
        $pm = PrivateMessage::where('id', '=', $id)->firstOrFail();

        if ($pm->sender_id == $user->id || $pm->receiver_id == $user->id) {
            if ($user->id === $pm->receiver_id && $pm->read === 0) {
                $pm->read = 1;
                $pm->save();
            }

            return view('pm.message', ['pm' => $pm, 'user' => $user]);
        }

        return redirect()->route('inbox')
            ->withErrors('What Are You Trying To Do Here!');
    }

    /**
     * Create Message Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $receiver_id
     * @param string                   $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function makePrivateMessage(Request $request, $receiver_id = '', $username = '')
    {
        $user = $request->user();

        return view('pm.send', ['user' => $user, 'receiver_id' => $receiver_id, 'username' => $username]);
    }

    /**
     * Create A Message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendPrivateMessage(Request $request)
    {
        $user = $request->user();

        $dest = 'default';
        if ($request->has('dest') && $request->input('dest') == 'profile') {
            $dest = 'profile';
        }

        if ($request->has('receiver_id')) {
            $recipient = User::where('username', '=', $request->input('receiver_id'))->firstOrFail();
        } else {
            return redirect()->route('create', ['username' => $request->user()->username, 'id' => $request->user()->id])
                ->withErrors($v->errors());
        }

        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $recipient->id;
        $pm->subject = $request->input('subject');
        $pm->message = $request->input('message');
        $pm->read = 0;

        $v = validator($pm->toArray(), [
            'sender_id'   => 'required',
            'receiver_id' => 'required',
            'subject'     => 'required',
            'message'     => 'required',
            'read'        => 'required',
        ]);

        if ($v->fails()) {
            if ($dest == 'profile') {
                return redirect()->route('users.show', ['username' => $recipient->username])
                    ->withErrors($v->errors());
            }

            return redirect()->route('create', ['username' => $request->user()->username, 'id' => $request->user()->id])
                ->withErrors($v->errors());
        }
        $pm->save();
        if ($dest == 'profile') {
            return redirect()->route('users.show', ['username' => $recipient->username])
                ->withSuccess('Your PM Was Sent Successfully!');
        }

        return redirect()->route('inbox')
            ->withSuccess('Your PM Was Sent Successfully!');
    }

    /**
     * Reply To A Message.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function replyPrivateMessage(Request $request, $id)
    {
        $user = $request->user();

        $message = PrivateMessage::where('id', '=', $id)->firstOrFail();

        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
        $pm->subject = $message->subject;
        $pm->message = $request->input('message');
        $pm->related_to = $message->id;
        $pm->read = 0;

        $v = validator($pm->toArray(), [
            'sender_id'   => 'required',
            'receiver_id' => 'required',
            'subject'     => 'required',
            'message'     => 'required',
            'related_to'  => 'required',
            'read'        => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('inbox')
                ->withErrors($v->errors());
        }
        $pm->save();

        return redirect()->route('inbox')
            ->withSuccess('Your PM Was Sent Successfully!');
    }

    /**
     * Delete A Message.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deletePrivateMessage(Request $request, $id)
    {
        $user = $request->user();
        $pm = PrivateMessage::where('id', '=', $id)->firstOrFail();

        $dest = 'default';
        if ($request->has('dest') && $request->input('dest') == 'outbox') {
            $dest = 'outbox';
        }

        if ($pm->sender_id == $user->id || $pm->receiver_id == $user->id) {
            $pm->delete();

            if ($dest == 'outbox') {
                return redirect()->route('outbox')->withSuccess('PM Was Deleted Successfully!');
            }

            return redirect()->route('inbox')
                ->withSuccess('PM Was Deleted Successfully!');
        } else {
            return redirect()->route('inbox')
                ->withErrors('What Are You Trying To Do Here!');
        }
    }

    /**
     * Mark All Messages As Read.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $pms = PrivateMessage::where('receiver_id', '=', $user->id)->get();
        foreach ($pms as $pm) {
            $pm->read = 1;
            $pm->save();
        }

        return redirect()->route('inbox')
            ->withSuccess('Your Messages Have All Been Marked As Read!');
    }
}
