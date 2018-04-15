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

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;
use \Toastr;

class ReportController extends Controller
{
    /**
     * Reports System
     *
     *
     */
    public function postReport(Request $request)
    {
        $user = auth()->user();

        $v = validator($request->all(), [
            'type' => 'required',
            'reporter_id' => 'required|numeric',
            'title' => 'required',
            'message' => 'required',
            'solved' => 'required|numeric'
        ]);

        $report = new Report();
        $report->type = $request->input('type');
        $report->reporter_id = $user->id;
        $report->title = $request->input('title');
        $report->message = $request->input('message');
        $report->solved = 0;
        $report->save();

        // Activity Log
        \LogActivity::addToLog("Member {$user->username} has made a new {$report->type} report.");

        return redirect()->route('home')->with(Toastr::success('Your report has been successfully sent', 'Yay!', ['options']));
    }
}
