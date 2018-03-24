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

use App\CatalogTorrent;
use App\Catalog;
use App\Torrent;

class CatalogController extends Controller
{

    /**
     * Display catalogs list
     *
     * @access public
     * @return catalogs.catalogs View
     */
    public function catalogs()
    {
        $catalogs = Catalog::all();

        return view('catalogs.catalogs', ['catalogs' => $catalogs]);
    }

    /**
     * Displays movies in catalog
     *
     * @access public
     * @param $slug
     * @param $id
     * @return catalogs.catalog View
     */
    public function catalog($slug, $id)
    {
        $user = auth()->user();
        $catalog = Catalog::findOrFail($id);
        $records = CatalogTorrent::where('catalog_id', $id)->latest('imdb')->get();

        return view('catalogs.catalog', ['user' => $user, 'catalog' => $catalog, 'records' => $records]);
    }

    /**
     * Displays torrents
     *
     * @access public
     * @param $slug
     * @param $id
     * @return catalogs.torrents View
     */
    public function torrents($imdb)
    {
        $user = auth()->user();
        $torrents = Torrent::where('imdb', $imdb)->latest('size')->get();

        return view('catalogs.torrents', ['torrents' => $torrents, 'user' => $user]);
    }
}
