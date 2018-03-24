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
use Carbon\Carbon;

class PrivateMessageController extends Controller
{

    /**
     * Search pm by username
     *
     * @access public
     * @return View pm.inbox
     *
     */
    public function searchPM(Request $request, $username, $id)
    {
        $user = auth()->user();
        $search = $request->input('subject');
        $pms = PrivateMessage::where('reciever_id', $request->user()->id)->where([
            ['subject', 'like', '%' . $search . '%'],
        ])->latest()->paginate(20);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * Get Private Messages
     *
     * @access public
     * @return View pm.inbox
     *
     */
    public function getPrivateMessages(Request $request, $username, $id)
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('reciever_id', $request->user()->id)->latest()->paginate(25);

        return view('pm.inbox', ['pms' => $pms, 'user' => $user]);
    }

    public function markAllAsRead(Request $request, $username, $id)
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('reciever_id', $request->user()->id)->get();
        foreach ($pms as $pm) {
            $pm->read = 1;
            $pm->save();
        }

        return $this->getPrivateMessages($request, $username, $id);
    }

    /**
     * View The Message
     *
     * @access public
     * @return View pm.message
     *
     */
    public function getPrivateMessageById($username, $id, $pmid)
    {
        $user = auth()->user();
        $pm = PrivateMessage::where('id', $pmid)->firstOrFail();

        // If the message is not read, change the the status
        if ($pm->read == 0) {
            $pm->read = 1;
            $pm->save();
        }
        return view('pm.message', ['pm' => $pm, 'user' => $user]);
    }

    /**
     * View Outbox
     *
     * @access public
     * @return View pm.outbox
     *
     */
    public function getPrivateMessagesSent(Request $request)
    {
        $user = auth()->user();
        $pms = PrivateMessage::where('sender_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('pm.outbox', ['pms' => $pms, 'user' => $user]);
    }

    /**
     * Create Message
     *
     * @access public
     * @return View pm.send
     *
     */
    public function makePrivateMessage($username, $id)
    {
        $user = auth()->user();
        $usernames = User::oldest('username')->get();

        return view('pm.send', ['usernames' => $usernames, 'user' => $user]);
    }

    /**
     * Send Message
     *
     * @access public
     * @return View pm.inbox
     *
     */
    public function sendPrivateMessage(Request $request)
    {
        $user = auth()->user();

        $attributes = [
            'sender_id' => $user->id,
            'reciever_id' => $request->input('reciever_id'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'read' => 0,
        ];

        $pm = PrivateMessage::create($attributes);

        return redirect()->route('inbox', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your PM Was Sent Successfully!', 'Yay!', ['options']));
    }

    /**
     * Reply To A Message
     *
     * @access public
     * @return View page.message
     *
     */
    public function replyPrivateMessage(Request $request, $pmid)
    {
        $user = auth()->user();

        $pm = PrivateMessage::where('id', $pmid)->firstOrFail();

        $attributes = [
            'sender_id' => $user->id,
            'reciever_id' => $pm->sender_id,
            'subject' => $pm->subject,
            'message' => $request->input('message'),
            'related_to' => $pm->id,
            'read' => 0,
        ];

        $pm = PrivateMessage::create($attributes);

        return redirect()->route('inbox', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your PM Was Sent Successfully!', 'Yay!', ['options']));
    }

    /**
     * Delete The Message
     *
     * @access public
     * @return View pm.inbox
     *
     */
    public function deletePrivateMessage($pmid)
    {
        $user = auth()->user();
        $pm = PrivateMessage::where('id', $pmid)->firstOrFail();
        $pm->delete();

        return redirect()->route('inbox', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('PM Was Deleted Successfully!', 'Yay!', ['options']));
    }
}
