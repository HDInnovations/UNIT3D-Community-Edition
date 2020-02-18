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

namespace App\Http\Controllers\Staff;

use App\Helpers\TorrentHelper;
use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * ModerationController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Torrent Moderation Panel.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $current = Carbon::now();
        $pending = Torrent::with(['user', 'category'])->pending()->get();
        $postponed = Torrent::with(['user', 'category'])->postponed()->get();
        $rejected = Torrent::with(['user', 'category'])->rejected()->get();

        return view('Staff.moderation.index', [
            'current'   => $current,
            'pending'   => $pending,
            'postponed' => $postponed,
            'rejected'  => $rejected,
        ]);
    }

    /**
     * Approve A Torrent.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $torrent = Torrent::withAnyStatus()->where('id', '=', $id)->first();

        if ($torrent->status !== 1) {
            $appurl = config('app.url');
            $user = $torrent->user;
            $user_id = $user->id;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chat->systemMessage(
                    sprintf('User [url=%s/users/', $appurl).$username.']'.$username.sprintf('[/url] has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            } else {
                $this->chat->systemMessage(
                    sprintf('An anonymous user has uploaded [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);

            return redirect()->route('staff.moderation.index')
                ->withSuccess('Torrent Approved');
        }

        return redirect()->route('staff.moderation.index')
            ->withErrors('Torrent Already Approved');
    }

    /**
     * Postpone A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function postpone(Request $request)
    {
        $v = validator($request->all(), [
            'id'      => 'required|exists:torrents',
            'slug'    => 'required|exists:torrents',
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.moderation.index')
                ->withErrors($v->errors());
        }
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->where('id', '=', $request->input('id'))->first();
        $torrent->markPostponed();
        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $torrent->user_id;
        $pm->subject = sprintf('Your upload, %s ,has been postponed by %s', $torrent->name, $user->username);
        $pm->message = sprintf('Greetings, 

 Your upload, %s ,has been postponed. Please see below the message from the staff member. 

%s', $torrent->name, $request->input('message'));
        $pm->save();

        return redirect()->route('staff.moderation.index')
            ->withSuccess('Torrent Postponed');
    }

    /**
     * Reject A Torrent.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request)
    {
        $v = validator($request->all(), [
            'id'      => 'required|exists:torrents',
            'slug'    => 'required|exists:torrents',
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.moderation.index')
                ->withErrors($v->errors());
        }
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->where('id', '=', $request->input('id'))->first();
        $torrent->markRejected();
        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $torrent->user_id;
        $pm->subject = sprintf('Your upload, %s ,has been rejected by %s', $torrent->name, $user->username);
        $pm->message = sprintf('Greetings, 

 Your upload %s has been rejected. Please see below the message from the staff member. 

%s', $torrent->name, $request->input('message'));
        $pm->save();

        return redirect()->route('staff.moderation.index')
            ->withSuccess('Torrent Rejected');
    }
}
