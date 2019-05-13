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

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Models\Torrent;
use Illuminate\Http\Request;
use App\Helpers\TorrentHelper;
use App\Models\PrivateMessage;
use App\Models\TorrentRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

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
    public function moderation()
    {
        $current = Carbon::now();
        $pending = Torrent::with(['user', 'category'])->pending()->get();
        $postponed = Torrent::with(['user', 'category'])->postponed()->get();
        $rejected = Torrent::with(['user', 'category'])->rejected()->get();

        return view('Staff.torrent.moderation', [
            'current'   => $current,
            'pending'   => $pending,
            'postponed' => $postponed,
            'rejected'  => $rejected,
        ]);
    }

    /**
     * Approve A Torrent.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approve($slug, $id)
    {
        $torrent = Torrent::withAnyStatus()->where('id', '=', $id)->where('slug', '=', $slug)->first();

        if ($torrent->status !== 1) {
            $appurl = config('app.url');
            $user = $torrent->user;
            $user_id = $user->id;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chat->systemMessage(
                    "User [url={$appurl}/".$username.'.'.$user_id.']'.$username."[/url] has uploaded [url={$appurl}/torrents/".$torrent->slug.'.'.$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            } else {
                $this->chat->systemMessage(
                    "An anonymous user has uploaded [url={$appurl}/torrents/".$torrent->slug.'.'.$torrent->id.']'.$torrent->name.'[/url] grab it now! :slight_smile:'
                );
            }

            TorrentHelper::approveHelper($torrent->slug, $torrent->id);

            return redirect()->route('moderation')
                ->withSuccess('Torrent Approved');
        } else {
            return redirect()->route('moderation')
                ->withErrors('Torrent Already Approved');
        }
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
            return redirect()->route('moderation')
                ->withErrors($v->errors());
        } else {
            $user = $request->user();
            $torrent = Torrent::withAnyStatus()->where('id', '=', $request->input('id'))->first();
            $torrent->markPostponed();

            $pm = new PrivateMessage();
            $pm->sender_id = $user->id;
            $pm->receiver_id = $torrent->user_id;
            $pm->subject = "Your upload, {$torrent->name} ,has been postponed by {$user->username}";
            $pm->message = "Greetings, \n\n Your upload, {$torrent->name} ,has been postponed. Please see below the message from the staff member. \n\n{$request->input('message')}";
            $pm->save();

            return redirect()->route('moderation')
                ->withSuccess('Torrent Postponed');
        }
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
            return redirect()->route('moderation')
                ->withErrors($v->errors());
        } else {
            $user = $request->user();
            $torrent = Torrent::withAnyStatus()->where('id', '=', $request->input('id'))->first();
            $torrent->markRejected();

            $pm = new PrivateMessage();
            $pm->sender_id = $user->id;
            $pm->receiver_id = $torrent->user_id;
            $pm->subject = "Your upload, {$torrent->name} ,has been rejected by {$user->username}";
            $pm->message = "Greetings, \n\n Your upload {$torrent->name} has been rejected. Please see below the message from the staff member. \n\n{$request->input('message')}";
            $pm->save();

            return redirect()->route('moderation')
                ->withSuccess('Torrent Rejected');
        }
    }

    /**
     * Resets the filled and approved attributes on a given request.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function resetRequest(Request $request, $id)
    {
        $user = $request->user();
        abort_unless($user->group->is_modo, 403);

        $torrentRequest = TorrentRequest::findOrFail($id);
        $torrentRequest->filled_by = null;
        $torrentRequest->filled_when = null;
        $torrentRequest->filled_hash = null;
        $torrentRequest->approved_by = null;
        $torrentRequest->approved_when = null;
        $torrentRequest->save();

        return redirect()->route('request', ['id' => $id])
            ->withSuccess('The request has been reset!');
    }
}
