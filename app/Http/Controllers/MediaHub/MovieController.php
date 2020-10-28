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

namespace App\Http\Controllers\MediaHub;

use App\Models\Movie;
use App\Http\Controllers\Controller;

class MovieController extends Controller
{
    /**
     * Display All Movies.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('mediahub.movie.index');
    }

    /**
     * Show A Movie.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        /*$movie = Movie::with(['collection', 'genres', 'companies'])->findOrFail($id);

        return view('mediahub.movie.show', [
            'movie' => $movie,
        ]);*/
        abort(307);
    }
}