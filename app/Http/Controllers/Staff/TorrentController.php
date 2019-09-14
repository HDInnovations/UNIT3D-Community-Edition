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

use App\Http\Controllers\Controller;
use App\Models\Torrent;
use Illuminate\Http\Request;

class TorrentController extends Controller
{
    /**
     * Get All Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $torrents = Torrent::latest()->paginate(25);

        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }

    /**
     * Search Torrents.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $torrents = Torrent::where([
            ['name', 'like', '%'.$request->input('name').'%'],
        ])->latest()->paginate(25);

        $torrents->setPath('?name='.$request->input('name'));

        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }
}
