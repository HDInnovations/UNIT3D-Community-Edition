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

use App\Http\Controllers\Controller;
use App\LogActivity;
use \Toastr;

class ActivityLogController extends Controller
{

    /**
     * Display All Activities
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getActivity()
    {
        $activities = \LogActivity::logActivityLists();

        return view('Staff.activity.index', ['activities' => $activities]);
    }

    /**
     * Delete Record From Activity Log
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteActivity($id)
    {
        $activity = LogActivity::findOrFail($id);
        $activity->delete();

        return redirect()->route('getActivity')
            ->with(Toastr::success('Activity Record Has Successfully Been Deleted', 'Yay!', ['options']));
    }
}
