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
use App\Torrent;
use Illuminate\Http\Request;
use Brian2694\Toastr\Toastr;

class ReportController extends Controller
{

    /**
     * @var Report
     */
    private $report;

    /**
     * @var Torrent
     */
    private $torrent;

    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * PrivateMessageController Constructor
     *
     * @param Report $report
     * @param Torrent $torrent
     * @param Toastr $toastr
     */
    public function __construct(Report $report, Torrent $torrent, Toastr $toastr)
    {
        $this->report = $report;
        $this->torrent = $torrent;
        $this->toastr = $toastr;
    }

    /**
     * Create A Report
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function postReport(Request $request)
    {
        $torrent = $this->torrent->find($request->get('torrent_id'));
        $reported_by = auth()->user();
        $reported_user = $torrent->user;

        $this->report->create([
            'type' => $request->get('type'),
            'torrent_id' => $torrent->id,
            'reporter_id' => $reported_by->id,
            'reported_user' => $reported_user->id,
            'title' => $torrent->name,
            'message' => $request->get('message'),
            'solved' => 0
        ]);

        // Activity Log
        \LogActivity::addToLog("Member {$reported_by->username} has made a new {$request->get('type')} report.");

        return redirect()->route('home')
            ->with($this->toastr->success('Your report has been successfully sent', 'Yay!', ['options']));
    }
}
