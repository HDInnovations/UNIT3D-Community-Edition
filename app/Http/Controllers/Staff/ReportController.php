<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display All Reports.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $reports = Report::latest()->paginate(25);

        return view('Staff.report.index', ['reports' => $reports]);
    }

    /**
     * Show A Report.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $report = Report::findOrFail($id);

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $report->message, $match);

        return view('Staff.report.show', ['report' => $report, 'urls' => $match[0]]);
    }

    /**
     * Update A Report.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $v = validator($request->all(), [
            'verdict'  => 'required|min:3',
            'staff_id' => 'required',
        ]);

        $report = Report::findOrFail($id);

        if ($report->solved == 1) {
            return redirect()->route('staff.reports.index')
                ->withErrors('This Report Has Already Been Solved');
        }

        $report->verdict = $request->input('verdict');
        $report->staff_id = $user->id;
        $report->solved = 1;
        $report->save();

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $user->id;
        $pm->receiver_id = $report->reporter_id;
        $pm->subject = 'Your Report Has A New Verdict';
        $pm->message = "[b]REPORT TITLE:[/b] {$report->title}
        
                        [b]ORIGINAL MESSAGE:[/b] {$report->message}
                        
                        [b]VERDICT:[/b] {$report->verdict}";
        $pm->save();

        return redirect()->route('staff.reports.index')
            ->withSuccess('Report has been successfully resolved');
    }
}
