<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Peer;
use App\History;
use \Toastr;

class FlushController extends Controller
{
    /**
     * Delete all old peers from database
     *
     *
     */
    public function deleteOldPeers()
    {
        // Deleting old peers from the database
        foreach (Peer::all() as $peer) {
            if ((time() - strtotime($peer->updated_at)) > (60 * 60)) {
                $history = History::where("info_hash", $peer->info_hash)->where("user_id", $peer->user_id)->first();
                if ($history) {
                    $history->active = false;
                    $history->save();
                }
                $peer->delete();
            }
        }
        return redirect('staff_dashboard')->with(Toastr::success('Ghost Peers Have Been Flushed', 'Yay!', ['options']));
    }
}
