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
use App\Torrent;

class TorrentController extends Controller
{

    /**
     * Affiche la page d'administration des articles
     *
     * @access public
     */
    public function index()
    {
        $torrents = Torrent::latest()->paginate(25);
        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }

    /**
     * Search for torrents
     *
     * @access public
     * @return View page.torrents
     *
     */
    public function search(Request $request)
    {
        $search = $request->input('name');
        $torrents = Torrent::where([
            ['name', 'like', '%' . $request->input('name') . '%'],
        ])->latest()->paginate(25);

        $torrents->setPath('?name=' . $request->input('name'));

        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }
}
