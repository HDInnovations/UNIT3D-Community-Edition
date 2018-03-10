<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\User;
use App\Group;
use App\Torrent;
use App\Requests;
use App\Category;
use App\Peer;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

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
        $pending = Torrent::pending()->get(); //returns all Pending Torrents
        $postponed = Torrent::postponed()->get();
        $rejected = Torrent::rejected()->get();
        $modder = Torrent::where('status', '=', '0')->count(); //DB::table('torrents')->where('status', '=', '0')->count();

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
        Torrent::approve($id);

        return Redirect::route('moderation')->with(Toastr::success('Torrent Approved', 'Approve', ['options']));
    }

    /**
     * Torrent Moderation -> postpone
     *
     * @param $request Request containing torrent's id, slug and rejection message
     */
    public function postpone(Request $request)
    {
        $v = Validator::make($request->all(), [
            'id' => "required|exists:torrents",
            'slug' => "required|exists:torrents",
            'message' => "required|alpha_num"
        ]);

        if ($v) {
            $user = Auth::user();
            $torrent = Torrent::find($request->id)->get();
            $torrent->markPostponed();

            PrivateMessage::create(['sender' => $user->id, 'receiver' => $torrent->user_id, 'subject' => "Your upload has been postponed by {$user->username}", 'message' => "Greating user, \n\n Your upload {$torrent->username} has been postponed. Please see below the message from the staff member. \n\n".$message]);

            return Redirect::route('moderation')->with(Toastr::success('Torrent Postpones', 'Postponed', ['options']));
        } else {
            $errors = "";
            foreach ($v->errors()->all() as $error) {
                $errors .= $error . "\n";
            }
            \Log::notice("Rejection of torrent failed due to: \n\n".$errors);
            return Redirect::route('moderation')->with(Toastr::error('Unable to Reject torrent', 'Reject', ['options']));
        }
    }

    /**
     * Torrent Moderation -> reject
     *
     * @param $request Request containing torrent's id, slug and rejection message
     */
    public function reject(Request $request)
    {
        $v = Validator::make($request->all(), [
                'id' => "required|exists:torrents",
                'slug' => "required|exists:torrents",
                'message' => "required|alpha_num"
            ]);

        if ($v) {
            $user = Auth::user();
            $torrent = Torrent::find($request->id)->get();
            $torrent->markPostponed();

            PrivateMessage::create(['sender' => $user->id, 'receiver' => $torrent->user_id, 'subject' => "Your upload has been rejected by {$user->username}", 'message' => "Greating user, \n\n Your upload {$torrent->username} has been rejected. Please see below the message from the staff member. \n\n".$request->message]);

            return Redirect::route('moderation')->with(Toastr::success('Torrent Rejected', 'Reject', ['options']));
        } else {
            $errors = "";
            foreach ($v->errors()->all() as $error) {
                $errors .= $error . "\n";
            }
            \Log::notice("Rejection of torrent failed due to: \n\n".$errors);
            return Redirect::route('moderation')->with(Toastr::error('Unable to Reject torrent', 'Reject', ['options']));
        }
    }

    /**
     * Resets the filled and approved attributes on a given request
     * @method resetRequest
     *
     */
    public function resetRequest($id)
    {
        $user = Auth::user();
        // reset code here
        if ($user->group->is_modo) {
            $request = Requests::findOrFail($id);

            $request->filled_by = null;
            $request->filled_when = null;
            $request->filled_hash = null;
            $request->approved_by = null;
            $request->approved_when = null;
            $request->save();

            return Redirect::route('request', ['id' => $id])->with(Toastr::warning("The request has been reset!", 'Request reset!', ['options']));
        } else {
            return Redirect::route('request', ['id' => $id])->with(Toastr::warning("You don't have access to this operation!", 'Error!', ['options']));
        }
        return Redirect::route('requests')->with(Toastr::success("Unable to find request", 'Request not found', ['options']));
    }
}
