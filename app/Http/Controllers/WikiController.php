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

use App\Models\Wiki;
use App\Models\WikiCategory;

class WikiController extends Controller
{
    /**
     * Display All Wikis.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $wiki_categories = WikiCategory::with(['wikis'])->get()->sortBy('position');

        return view('wiki.index', ['wiki_categories' => $wiki_categories]);
    }

    /**
     * Show A Wiki.
     */
    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $wiki = Wiki::findOrFail($id);

        return view('wiki.show', ['wiki' => $wiki]);
    }
}
