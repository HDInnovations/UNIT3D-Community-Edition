<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrivateMessage;
use App\User;
use \Toastr;

class PrivateMessageController extends Controller
{

    /**
     * Search PM
     *
     * @param $request Request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchPM(Request $request)
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('receiver_id', $user->id)->where([
            ['subject', 'like', '%' . $request->input('subject') . '%'],
        ])->latest()->paginate(20);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View Inbox
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessages()
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('receiver_id', $user->id)->latest()->paginate(25);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View Outbox
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessagesSent()
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('sender_id', $user->id)->latest()->paginate(20);

        return view('pm.outbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * View A Message
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivateMessageById($id)
    {
        $user = auth()->user();
        $pm = PrivateMessage::where('id', $id)->firstOrFail();

        if($pm->sender_id == $user->id || $pm->receiver_id == $user->id) {
            if ($pm->read == 0) {
                $pm->read = 1;
                $pm->save();
            }

            return view('pm.message', ['pm' => $pm, 'user' => $user]);

        } else {
            return redirect()->route('inbox')
                ->with(Toastr::error('What Are You Trying To Do Here!', 'Whoops!', ['options']));
        }
    }

    /**
     * Create Message Form
     *
     * @param $receiver_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function makePrivateMessage($receiver_id = '', $username = '')
    {
        $user = auth()->user();
        $usernames = User::oldest('username')->get();

        return view('pm.send', ['usernames' => $usernames, 'user' => $user, 'receiver_id' => $receiver_id,
            'username' => $username]);
    }

    /**
     * Create A Message
     *
     * @param $request Request
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendPrivateMessage(Request $request)
    {
        $user = auth()->user();

        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $request->input('receiver_id');
        $pm->subject = $request->input('subject');
        $pm->message = $request->input('message');
        $pm->read = 0;


        $v = validator($pm->toArray(), [
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'read' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('create', ['receiver_id' => '', 'username' => ''])
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $pm->save();
            return redirect()->route('inbox')
                ->with(Toastr::success('Your PM Was Sent Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Reply To A Message
     *
     * @param $request Request
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function replyPrivateMessage(Request $request, $id)
    {
        $user = auth()->user();

        $message = PrivateMessage::where('id', $id)->firstOrFail();

        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $message->sender_id;
        $pm->subject = $message->subject;
        $pm->message = $request->input('message');
        $pm->related_to = $message->id;
        $pm->read = 0;


        $v = validator($pm->toArray(), [
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'related_to' => 'required',
            'read' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('inbox')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $pm->save();

            return redirect()->route('inbox')
                ->with(Toastr::success('Your PM Was Sent Successfully!', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Message
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function deletePrivateMessage($id)
    {
        $user = auth()->user();
        $pm = PrivateMessage::where('id', $id)->firstOrFail();

        if($pm->sender_id == $user->id || $pm->receiver_id == $user->id) {
            $pm->delete();
            return redirect()->route('inbox')
                ->with(Toastr::success('PM Was Deleted Successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->route('inbox')
                ->with(Toastr::error('What Are You Trying To Do Here!', 'Whoops!', ['options']));
        }
    }

    /**
     * Mark All Messages As Read
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('receiver_id', $user->id)->get();
        foreach ($pms as $pm) {
            $pm->read = 1;
            $pm->save();
        }

        return redirect()->route('inbox')
            ->with(Toastr::success('Your Messages Have All Been Marked As Read!', 'Yay!', ['options']));
    }
}
