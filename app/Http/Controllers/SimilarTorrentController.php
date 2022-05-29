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

use App\Models\Movie;
use App\Models\Torrent;
use App\Models\Tv;

class SimilarTorrentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(int $categoryId, int $tmdbId): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $torrent = Torrent::where('category_id', '=', $categoryId)
            ->where('tmdb', '=', $tmdbId)
            ->first();

        \abort_if(! $torrent || $torrent->count() === 0, 404, 'No Similar Torrents Found');

        $meta = null;
        if ($torrent->category->tv_meta) {
            $meta = Tv::with('genres', 'cast', 'networks', 'seasons')->where('id', '=', $tmdbId)->first();
        }

        if ($torrent->category->movie_meta) {
            $meta = Movie::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $tmdbId)->first();
        }

        return \view('torrent.similar', [
            'meta'       => $meta,
            'torrent'    => $torrent,
            'categoryId' => $categoryId,
            'tmdbId'     => $tmdbId,
        ]);
    }
}
