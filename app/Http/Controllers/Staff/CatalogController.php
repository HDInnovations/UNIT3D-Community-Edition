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

use App\Models\Catalog;
use App\Models\CatalogTorrent;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * CatalogController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get All Catalogs.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCatalogs()
    {
        $catalogs = Catalog::latest('name')->get();

        return view('Staff.catalog.catalogs', ['catalogs' => $catalogs]);
    }

    /**
     * Create A Catalog.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function postCatalog(Request $request)
    {
        $v = validator($request->all(), [
            'catalog' => 'required|min:3|max:20|regex:/^[(a-zA-Z\-)]+$/u',
        ]);
        $catalog = Catalog::where('name', '=', $request->input('catalog'))->first();
        if ($catalog) {
            return redirect()->route('catalogs')
                ->with($this->toastr->error('Catalog '.$catalog->name.' is already in database', 'Whoops!', ['options']));
        }
        $catalog = new Catalog();
        $catalog->name = $request->input('catalog');
        $catalog->slug = str_slug($request->input('catalog'));
        $catalog->save();

        return redirect()->route('getCatalog')
            ->with($this->toastr->success('Catalog '.$request->input('catalog').' has been successfully added', 'Yay!', ['options']));
    }

    /**
     * Edit A Catalog.
     *
     * @param \Illuminate\Http\Request $request
     * @param $catalog_id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function editCatalog(Request $request, $catalog_id)
    {
        $v = validator($request->all(), [
            'catalog' => 'required|min:3|max:20|regex:/^[(a-zA-Z\-)]+$/u',
        ]);
        $catalog = Catalog::findOrFail($catalog_id);
        if (! $catalog) {
            return redirect()->route('getCatalog')
                ->with($this->toastr->error('Catalog '.$request->input('catalog').' is not in our DB!', 'Whoops!', ['options']));
        }
        $catalog->name = $request->input('catalog');
        $catalog->save();

        return redirect()->route('getCatalog')
            ->with($this->toastr->success('Catalog '.$request->input('catalog').' has been successfully edited', 'Yay!', ['options']));
    }

    /**
     * Delete A Catalog.
     *
     * @param $catalog_id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteCatalog($catalog_id)
    {
        $catalog = Catalog::findOrFail($catalog_id);
        if (! $catalog) {
            return redirect()->route('getCatalog')
                ->with($this->toastr->error('That Catalog Is Not In Our DB!', 'Whoops!', ['options']));
        }
        $catalog->delete();

        return redirect()->route('getCatalog')
            ->with($this->toastr->success('Catalog '.$catalog->name.' has been successfully deleted', 'Yay!', ['options']));
    }

    /**
     * Catalog Torrent System.
     */
    public function getCatalogTorrent()
    {
        $catalogs = Catalog::latest('name')->get();

        return view('Staff.catalog.catalog_torrent')->with('catalogs', $catalogs);
    }

    //Add New Catalog Torrent
    public function postCatalogTorrent(Request $request)
    {
        // Find the right catalog
        $catalog = Catalog::findOrFail($request->input('catalog_id'));
        $v = validator($request->all(), [
            'imdb'       => 'required|numeric',
            'tvdb'       => 'required|numeric',
            'catalog_id' => 'required|numeric|exists:catalog_id',
        ]);
        $torrent = CatalogTorrent::where('imdb', '=', $request->input('imdb'))->first();
        if ($torrent) {
            return redirect()->route('getCatalogTorrent')->with($this->toastr->error('IMDB# '.$torrent->imdb.' is already in database', 'Whoops!', ['options']));
        }
        $torrent = new CatalogTorrent();
        $torrent->imdb = $request->input('imdb');
        $torrent->catalog_id = $request->input('catalog_id');
        $torrent->save();
        // Count and save the torrent number in this catalog
        $catalog->num_torrent = CatalogTorrent::where('catalog_id', '=', $catalog->id)->count();
        $catalog->save();

        return redirect()->route('getCatalogTorrent')->with($this->toastr->success('IMDB# '.$request->input('imdb').' has been successfully added', 'Yay!', ['options']));
    }

    // Get Catalogs Records
    public function getCatalogRecords($catalog_id)
    {
        $catalogs = Catalog::findOrFail($catalog_id);
        $records = CatalogTorrent::where('catalog_id', '=', $catalog_id)->latest('imdb')->get();

        return view('Staff.catalog.catalog_records', ['catalog' => $catalogs, 'records' => $records]);
    }
}
