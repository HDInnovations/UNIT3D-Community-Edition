<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function activityLog()
    {
        $logs = \LogActivity::logActivityLists();
        return view('Staff.activity.index', compact('logs'));
    }
}
