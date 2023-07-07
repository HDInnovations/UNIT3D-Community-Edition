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

use App\Models\Category;
use App\Models\Movie;
use App\Models\Torrent;
use App\Models\Tv;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\PlatformLogo;

class SimilarTorrentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(int $categoryId, int $tmdbId): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $category = Category::query()->findOrFail($categoryId);

        switch (true) {
            case $category->movie_meta:
                $hasTorrents = Torrent::query()->where('category_id', '=', $categoryId)->where('tmdb', '=', $tmdbId)->exists();

                abort_unless($hasTorrents, 404, 'No Similar Torrents Found');

                $meta = Movie::with([
                    'genres',
                    'credits' => ['person', 'occupation'],
                    'companies'
                ])
                    ->find($tmdbId);
                $trailer = ( new \App\Services\Tmdb\Client\Movie($tmdbId))->get_trailer();
                $tmdb = $tmdbId;

                break;
            case $category->tv_meta:
                $hasTorrents = Torrent::query()->where('category_id', '=', $categoryId)->where('tmdb', '=', $tmdbId)->exists();

                abort_unless($hasTorrents, 404, 'No Similar Torrents Found');

                $meta = Tv::with([
                    'genres',
                    'credits' => ['person', 'occupation'],
                    'companies',
                    'networks'
                ])
                    ->find($tmdbId);
                $trailer = ( new \App\Services\Tmdb\Client\TV($tmdbId))->get_trailer();
                $tmdb = $tmdbId;

                break;
            case $category->game_meta:
                $hasTorrents = Torrent::query()->where('category_id', '=', $categoryId)->where('igdb', '=', $tmdbId)->exists();

                abort_unless($hasTorrents, 404, 'No Similar Torrents Found');

                $meta = Game::with([
                    'cover'    => ['url', 'image_id'],
                    'artworks' => ['url', 'image_id'],
                    'genres'   => ['name'],
                    'videos'   => ['video_id', 'name'],
                    'involved_companies.company',
                    'involved_companies.company.logo',
                    'platforms',
                ])
                    ->find($tmdbId);
                $link = collect($meta->videos)->take(1)->pluck('video_id');
                $trailer = isset($link[0]) ? 'https://www.youtube.com/embed/'.$link[0] : '/img/no-video.png';
                $platforms = PlatformLogo::whereIn('id', collect($meta->platforms)->pluck('platform_logo')->toArray())->get();
                $igdb = $tmdbId;

                break;
            default:
                abort(404, 'No Similar Torrents Found');

                break;
        }

        $personalFreeleech = cache()->get('personal_freeleech:'.auth()->id());

        return view('torrent.similar', [
            'meta'               => $meta,
            'personal_freeleech' => $personalFreeleech,
            'trailer'            => $trailer,
            'platforms'          => $platforms ?? null,
            'category'           => $category,
            'tmdb'               => $tmdb ?? null,
            'igdb'               => $igdb ?? null,
        ]);
    }
}
