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
use Brian2694\Toastr\Toastr;

class FlushController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * FlushController Constructor
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Delete All Old Peers From Database
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteOldPeers()
    {
        foreach (Peer::select(['id', 'info_hash', 'user_id', 'updated_at'])->get() as $peer) {
            if ((time() - strtotime($peer->updated_at)) > (60 * 60 * 2)) {
                $history = History::where("info_hash", $peer->info_hash)->where("user_id", $peer->user_id)->first();
                if ($history) {
                    $history->active = false;
                    $history->save();
                }
                $peer->delete();
            }
        }
        return redirect('staff_dashboard')
            ->with($this->toastr->success('Ghost Peers Have Been Flushed', 'Yay!', ['options']));
    }
}
