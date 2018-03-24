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

namespace App\Http\Controllers\Staff;

use App\User;
use App\Torrent;
use App\TorrentRequest;
use App\PrivateMessage;
use App\Helpers\TorrentHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \Toastr;

class ModerationController extends Controller
{
    /**
     * Torrent Moderation.
     *
     */
    public function moderation()
    {
        $current = Carbon::now();
        $pending = Torrent::pending()->get();
        $postponed = Torrent::postponed()->get();
        $rejected = Torrent::rejected()->get();
        $modder = Torrent::where('status', 0)->count();

        return view('Staff.torrent.moderation', compact(['current', 'pending', 'postponed', 'rejected', 'modder']));
    }

    /**
     * Torrent Moderation -> approve
     *
     * @param $slug Slug of the torrent
     * @param $id Id of the torrent
     */
    public function approve($slug, $id)
    {
        TorrentHelper::approveHelper($slug, $id);

        return redirect()->route('moderation')->with(Toastr::success('Torrent Approved', 'Yay!', ['options']));
    }

    /**
     * Torrent Moderation -> postpone
     *
     * @param $request Request containing torrent's id, slug and rejection message
     */
    public function postpone(Request $request)
    {
        $v = validator($request->all(), [
            'id' => "required|exists:torrents",
            'slug' => "required|exists:torrents",
            'message' => "required|alpha_dash"
        ]);

        if ($v) {
            $user = auth()->user();
            $torrent = Torrent::withAnyStatus()->where('id', $request->input('id'))->first();
            $torrent->markPostponed();

            PrivateMessage::create([
            'sender_id' => $user->id,
            'reciever_id' => $torrent->user_id,
            'subject' => "Your upload has been postponed by {$user->username}",
            'message' => "Greating user, \n\n Your upload {$torrent->username} has been postponed. Please see below the message from the staff member. \n\n{$request->input('message')}"]);

            return redirect()->route('moderation')->with(Toastr::success('Torrent Postpones', 'Postponed', ['options']));
        } else {
            $errors = "";
            foreach ($v->errors()->all() as $error) {
                $errors .= $error . "\n";
            }
            \Log::notice("Rejection of torrent failed due to: \n\n".$errors);
            return redirect()->route('moderation')->with(Toastr::error('Unable to Reject torrent', 'Reject', ['options']));
        }
    }

    /**
     * Torrent Moderation -> reject
     *
     * @param $request Request containing torrent's id, slug and rejection message
     */
    public function reject(Request $request)
    {
        $v = validator($request->all(), [
                'id' => "required|exists:torrents",
                'slug' => "required|exists:torrents",
                'message' => "required|alpha_dash"
            ]);

        if ($v) {
            $user = auth()->user();
            $torrent = Torrent::withAnyStatus()->where('id', $request->input('id'))->first();
            $torrent->markRejected();

            PrivateMessage::create(['sender_id' => $user->id, 'reciever_id' => $torrent->user_id, 'subject' => "Your upload has been rejected by {$user->username}", 'message' => "Greating user, \n\n Your upload {$torrent->username} has been rejected. Please see below the message from the staff member. \n\n{$request->input('message')}"]);

            return redirect()->route('moderation')->with(Toastr::success('Torrent Rejected', 'Reject', ['options']));
        } else {
            $errors = "";
            foreach ($v->errors()->all() as $error) {
                $errors .= $error . "\n";
            }
            \Log::notice("Rejection of torrent failed due to: \n\n".$errors);
            return redirect()->route('moderation')->with(Toastr::error('Unable to Reject torrent', 'Reject', ['options']));
        }
    }

    /**
     * Resets the filled and approved attributes on a given request
     * @method resetRequest
     *
     */
    public function resetRequest($id)
    {
        $user = auth()->user();
        // reset code here
        if ($user->group->is_modo) {
            $torrentRequest = TorrentRequest::findOrFail($id);
            $torrentRequest->filled_by = null;
            $torrentRequest->filled_when = null;
            $torrentRequest->filled_hash = null;
            $torrentRequest->approved_by = null;
            $torrentRequest->approved_when = null;
            $torrentRequest->save();

            return redirect()->route('request', ['id' => $id])->with(Toastr::success("The request has been reset!", 'Yay!', ['options']));
        } else {
            return redirect()->route('request', ['id' => $id])->with(Toastr::error("You don't have access to this operation!", 'Whoops!', ['options']));
        }
        return redirect()->route('requests')->with(Toastr::error("Unable to find request!", 'Whoops!', ['options']));
    }
}
