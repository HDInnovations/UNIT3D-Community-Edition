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

/**
 * @see \Tests\Todo\Feature\Http\Controllers\ReportControllerTest
 */
class ReportController extends Controller
{
    /**
     * Create A Request Report.
     */
    public function request(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $torrentRequest = TorrentRequest::findOrFail($id);
        $reportedBy = $request->user();
        $reportedUser = $torrentRequest->user;

        $request->validate([
            'message' => [
                'required',
                'max:65535',
            ],
        ]);

        Report::create([
            'type'          => 'Request',
            'request_id'    => $torrentRequest->id,
            'torrent_id'    => null,
            'reporter_id'   => $reportedBy->id,
            'reported_user' => $reportedUser->id,
            'title'         => $torrentRequest->name,
            'message'       => $request->string('message'),
            'solved'        => 0,
        ]);

        return to_route('requests.show', ['torrentRequest' => $torrentRequest])
            ->withSuccess(trans('user.report-sent'));
    }

    /**
     * Create A Torrent Report.
     */
    public function torrent(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $torrent = Torrent::findOrFail($id);
        $reportedBy = $request->user();
        $reportedUser = $torrent->user;

        $request->validate([
            'message' => [
                'required',
                'max:65535',
            ],
        ]);

        Report::create([
            'type'          => 'Torrent',
            'torrent_id'    => $torrent->id,
            'request_id'    => null,
            'reporter_id'   => $reportedBy->id,
            'reported_user' => $reportedUser->id,
            'title'         => $torrent->name,
            'message'       => $request->string('message'),
            'solved'        => 0,
        ]);

        return to_route('torrents.show', ['id' => $id])
            ->withSuccess(trans('user.report-sent'));
    }

    /**
     * Create A User Report.
     */
    public function user(Request $request, string $username): \Illuminate\Http\RedirectResponse
    {
        $reportedUser = User::where('username', '=', $username)->sole();
        $reportedBy = $request->user();

        $request->validate([
            'message' => [
                'required',
                'max:65535',
            ],
        ]);

        Report::create([
            'type'          => 'User',
            'torrent_id'    => null,
            'request_id'    => null,
            'reporter_id'   => $reportedBy->id,
            'reported_user' => $reportedUser->id,
            'title'         => $reportedUser->username,
            'message'       => $request->string('message'),
            'solved'        => 0,
        ]);

        return to_route('users.show', ['user' => $reportedBy])
            ->withSuccess(trans('user.report-sent'));
    }
}
