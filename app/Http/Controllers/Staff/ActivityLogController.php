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

use App\Models\LogActivity;
use Brian2694\Toastr\Toastr;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ActivityLogController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Display All Activities.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $activities = LogActivity::with('user')->latest()->paginate(50);

        return view('Staff.activity.index', ['activities' => $activities]);
    }

    /**
     * Delete Record From Activity Log.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $activity = LogActivity::findOrFail($id);

        abort_unless($user->group->is_modo, 403);
        $activity->delete();

        return redirect()->route('activity.index')
            ->with($this->toastr->success('Activity Record Has Successfully Been Deleted', 'Yay!', ['options']));
    }
}
