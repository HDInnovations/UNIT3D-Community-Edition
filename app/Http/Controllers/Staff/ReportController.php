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
use Illuminate\Http\Request;
use App\PrivateMessage;
use App\Report;
use \Toastr;

class ReportController extends Controller
{
    /**
     * Reports System
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReports()
    {
        $reports = Report::latest()->paginate(25);

        return view('Staff.reports.index', ['reports' => $reports]);
    }

    /**
     * @param $report_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReport($report_id)
    {
        $report = Report::findOrFail($report_id);

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $report->message, $match);

        return view('Staff.reports.report', ['report' => $report, 'urls' => $match[0]]);
    }

    /**
     * @param $report_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function solveReport(Request $request, $report_id)
    {
        $user = auth()->user();

        $v = validator($request->all(), [
            'verdict' => 'required|min:3',
            'staff_id' => 'required'
        ]);

        $report = Report::findOrFail($report_id);

        if ($report->solved == 1) {
            return redirect()->route('getReports')->with(Toastr::error('This Report Has Already Been Solved', 'Whoops!', ['options']));
        }

        $report->verdict = $request->input('verdict');
        $report->staff_id = $user->id;
        $report->solved = 1;
        $report->save();

        // Send Private Message
        $pm = new PrivateMessage;
        $pm->sender_id = $user->id;
        $pm->receiver_id = $report->reporter_id;
        $pm->subject = "Your Report Has A New Verdict";
        $pm->message = $report->verdict;
        $pm->save();

        return redirect()->route('getReports')->with(Toastr::success('Report has been successfully resolved', 'Yay!', ['options']));
    }
}
