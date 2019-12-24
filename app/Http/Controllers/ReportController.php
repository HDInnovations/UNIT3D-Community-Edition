<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class ReportController extends Controller
{
    /**
     * @var Report
     */
    private Report $report;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;

    /**
     * ReportController Constructor.
     *
     * @param  Report  $report
     * @param  \Illuminate\Routing\Redirector  $redirector
     */
    public function __construct(Report $report, Redirector $redirector)
    {
        $this->report = $report;
        $this->redirector = $redirector;
    }

    /**
     * Create A Request Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function request(Request $request, int $id)
    {
        $torrentRequest = TorrentRequest::findOrFail($id);
        $reported_by = $request->user();
        $reported_user = $torrentRequest->user;

        $v = validator($request->all(), [
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return $this->redirector->route('request', ['id' => $id])
                ->withErrors($v->errors());
        } else {
            $this->report->create([
                'type' => 'Request',
                'request_id' => $torrentRequest->id,
                'torrent_id' => 0,
                'reporter_id' => $reported_by->id,
                'reported_user' => $reported_user->id,
                'title' => $torrentRequest->name,
                'message' => $request->get('message'),
                'solved' => 0,
            ]);

            return $this->redirector->route('request', ['id' => $id])
                ->withSuccess('Your report has been successfully sent');
        }
    }

    /**
     * Create A Torrent Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function torrent(Request $request, int $id)
    {
        $torrent = Torrent::findOrFail($id);
        $reported_by = $request->user();
        $reported_user = $torrent->user;

        $v = validator($request->all(), [
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return $this->redirector->route('torrent', ['id' => $id])
                ->withErrors($v->errors());
        } else {
            $this->report->create([
                'type' => 'Torrent',
                'torrent_id' => $torrent->id,
                'request_id' => 0,
                'reporter_id' => $reported_by->id,
                'reported_user' => $reported_user->id,
                'title' => $torrent->name,
                'message' => $request->get('message'),
                'solved' => 0,
            ]);

            return $this->redirector->route('torrent', ['id' => $id])
                ->withSuccess('Your report has been successfully sent');
        }
    }

    /**
     * Create A User Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function user(Request $request, $username, int $id)
    {
        $reported_user = User::findOrFail($id);
        $reported_by = $request->user();

        $v = validator($request->all(), [
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return $this->redirector->route('users.show', ['username' => $username])
                ->withErrors($v->errors());
        } else {
            $this->report->create([
                'type' => 'User',
                'torrent_id' => 0,
                'request_id' => 0,
                'reporter_id' => $reported_by->id,
                'reported_user' => $reported_user->id,
                'title' => $reported_user->username,
                'message' => $request->get('message'),
                'solved' => 0,
            ]);

            return $this->redirector->route('users.show', ['username' => $username])
                ->withSuccess('Your report has been successfully sent');
        }
    }
}
