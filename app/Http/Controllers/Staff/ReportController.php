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
use App\Http\Requests\Staff\UpdateReportRequest;
use App\Models\PrivateMessage;
use App\Models\Report;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\ReportControllerTest
 */
class ReportController extends Controller
{
    /**
     * Display All Reports.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $reports = Report::orderBy('solved')->latest()->paginate(25);

        return \view('Staff.report.index', ['reports' => $reports]);
    }

    /**
     * Show A Report.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $report = Report::findOrFail($id);

        \preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', (string) $report->message, $match);

        return \view('Staff.report.show', ['report' => $report, 'urls' => $match[0]]);
    }

    /**
     * Update A Report.
     */
    public function update(UpdateReportRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $staff = \auth()->user();
        $report = Report::findOrFail($id);

        if ($report->solved == 1) {
            return \to_route('staff.reports.index')
                ->withErrors('This Report Has Already Been Solved');
        }

        $report->update(['solved' => 1, 'staff_id' => $staff->id] + $request->validated());

        // Send Private Message
        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $report->reporter_id,
            'subject'     => 'Your Report Has A New Verdict',
            'message'     => '[b]REPORT TITLE:[/b] '.$report->title."\n\n[b]ORIGINAL MESSAGE:[/b] ".$report->message."\n\n[b]VERDICT:[/b] ".$report->verdict,
        ]);

        return \to_route('staff.reports.index')
            ->withSuccess('Report has been successfully resolved');
    }
}
