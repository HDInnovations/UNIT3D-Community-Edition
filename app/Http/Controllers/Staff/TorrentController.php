<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
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
        $torrents = Torrent::orderBy('created_at', 'DESC')->paginate(20);
        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }

    /**
     * Search for torrents
     *
     * @access public
     * @return View page.torrents
     *
     */
    public function search()
    {
        $search = Request::get('name');
        $torrents = Torrent::where([
            ['name', 'like', '%' . Request::get('name') . '%'],
        ])->orderBy('created_at', 'DESC')->paginate(25);

        $torrents->setPath('?name=' . Request::get('name'));

        return view('Staff.torrent.index', ['torrents' => $torrents]);
    }

}
