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
 * @author     Mr.G
 */

namespace App\Http\Controllers\Staff;

use App\Peer;
use App\History;
use Carbon\Carbon;
use Brian2694\Toastr\Toastr;
use App\Http\Controllers\Controller;

class FlushController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * FlushController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Delete All Old Peers From Database.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteOldPeers()
    {
        $current = new Carbon();
        $peers = Peer::select(['id', 'info_hash', 'user_id', 'updated_at'])->where('updated_at', '<', $current->copy()->subHours(2)->toDateTimeString())->get();

        foreach ($peers as $peer) {
            $history = History::where('info_hash', '=', $peer->info_hash)->where('user_id', '=', $peer->user_id)->first();
            if ($history) {
                $history->active = false;
                $history->save();
            }
            $peer->delete();
        }

        return redirect('staff_dashboard')
            ->with($this->toastr->success('Ghost Peers Have Been Flushed', 'Yay!', ['options']));
    }
}
