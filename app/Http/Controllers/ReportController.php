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

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @var Report
     */
    private $report;

    /**
     * ReportController Constructor.
     *
     * @param Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Create A Request Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
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
            return redirect()->route('request', ['id' => $id])
                ->withErrors($v->errors());
        }
        $this->report->create([
            'type'          => 'Request',
            'request_id'    => $torrentRequest->id,
            'torrent_id'    => 0,
            'reporter_id'   => $reported_by->id,
            'reported_user' => $reported_user->id,
            'title'         => $torrentRequest->name,
            'message'       => $request->get('message'),
            'solved'        => 0,
        ]);

        return redirect()->route('request', ['id' => $id])
            ->withSuccess('Your report has been successfully sent');
    }

    /**
     * Create A Torrent Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
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
            return redirect()->route('torrent', ['id' => $id])
                ->withErrors($v->errors());
        }
        $this->report->create([
            'type'          => 'Torrent',
            'torrent_id'    => $torrent->id,
            'request_id'    => 0,
            'reporter_id'   => $reported_by->id,
            'reported_user' => $reported_user->id,
            'title'         => $torrent->name,
            'message'       => $request->get('message'),
            'solved'        => 0,
        ]);

        return redirect()->route('torrent', ['id' => $id])
            ->withSuccess('Your report has been successfully sent');
    }

    /**
     * Create A User Report.
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function user(Request $request, $username)
    {
        $reported_user = User::where('username', '=', $username)->firstOrFail();
        $reported_by = $request->user();

        $v = validator($request->all(), [
            'message' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('users.show', ['username' => $username])
                ->withErrors($v->errors());
        }
        $this->report->create([
            'type'          => 'User',
            'torrent_id'    => 0,
            'request_id'    => 0,
            'reporter_id'   => $reported_by->id,
            'reported_user' => $reported_user->id,
            'title'         => $reported_user->username,
            'message'       => $request->get('message'),
            'solved'        => 0,
        ]);

        return redirect()->route('users.show', ['username' => $username])
            ->withSuccess('Your report has been successfully sent');
    }
}
