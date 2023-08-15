<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\Torrent;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AutoSyncTorrentsToMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sync_torrents_to_meilisearch {--wipe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs torrents and their relations to meilisearch';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $host = config('meilisearch.host');
        $key = config('meilisearch.key');

        if ($this->option('wipe')) {
            $response = Http::withToken($key)->delete($host.'/indexes/torrents');

            if ($response->failed()) {
                $this->comment('Error received while deleting Meilisearch index. Aborting. Status code: '.$response->status().'. '.$response->body());

                return;
            }

            $this->comment('Successfully deleted Meilisearch index.');
        }

        if (Http::withToken($key)->get($host.'/indexes/torrents')->notFound()) {
            $response = Http::withToken($key)->post($host.'/indexes', [
                'uid'        => 'torrents',
                'primaryKey' => 'id',
            ]);

            if ($response->failed()) {
                $this->comment('Error received while creating Meilisearch index. Aborting. Status code: '.$response->status().'. '.$response->body());

                return;
            }

            $this->comment('Successfully created new Meilisearch index.');
        }

        // Configure index settings
        $response = Http::withToken($key)->patch($host.'/indexes/torrents/settings/', [
            'filterableAttributes' => [
                'id',
                'name',
                'folder',
                'size',
                'leechers',
                'seeders',
                'times_completed',
                'created_at',
                'bumped_at',
                'user_id',
                'imdb',
                'tvdb',
                'tmdb',
                'mal',
                'igdb',
                'season_number',
                'episode_number',
                'stream',
                'free',
                'doubleup',
                'refundable',
                'highspeed',
                'featured',
                'status',
                'anon',
                'sticky',
                'sd',
                'internal',
                'release_year',
                'deleted_at',
                'personal_release',
                'info_hash',
                'history_seeders.user_id',
                'history_leechers.user_id',
                'history_active.user_id',
                'history_inactive.user_id',
                'history_complete.user_id',
                'history_incomplete.user_id',
                'user.username',
                'category.id',
                'category.movie_meta',
                'category.tv_meta',
                'type.id',
                'resolution.id',
                'movie.id',
                'movie.name',
                'movie.year',
                'movie.original_language',
                'movie.adult',
                'movie.companies.id',
                'movie.genres.id',
                'movie.collection_id',
                'movie.wishes.user_id',
                'tv.id',
                'tv.name',
                'tv.year',
                'tv.original_language',
                'tv.companies.id',
                'tv.genres.id',
                'tv.networks.id',
                'tv.wishes.user_id',
                'playlists.id',
                'bookmarks.user_id',
                'freeleech_tokens.user_id',
                'files.name',
                'keywords',
                'distributor_ids',
                'region_ids',
            ],
            'searchableAttributes' => [
                'name',
                'movie.name',
                'tv.name',
                'movie.year',
                'tv.year',
                'type.name',
                'resolution.name',
            ],
            'sortableAttributes' => [
                'name',
                'size',
                'seeders',
                'leechers',
                'times_completed',
                'created_at',
                'bumped_at',
                'sticky',
            ],
        ]);

        if ($response->failed()) {
            $this->comment('Error received while updating Meilisearch index settings. Aborting. Status code: '.$response->status().'. '.$response->body());

            return;
        }

        $this->comment('Successfully updated Meilisearch index settings.');

        $start = now();

        $maxId = DB::table('torrents')->max('id') ?? 1;
        // Going any larger will result in "Got a packet bigger than 'max_allowed_packet' bytes"
        // from mysql and increasing the variable doesn't fix it.
        $idsPerIteration = 250; // max(1, intdiv($maxId, 25));

        for ($id = 0; $id < $maxId; $id += $idsPerIteration) {
            $this->comment('Syncing '.$idsPerIteration.' torrents to meilisearch');

            $torrents = DB::table('torrents')
                ->selectRaw(Torrent::SEARCHABLE)
                ->where('id', '>', $id)
                ->where('id', '<=', $id + $idsPerIteration)
                ->value('searchable');

            if ($torrents === null) {
                $this->comment('Error received when fetching torrents for Meilisearch. Aborting');

                return;
            }

            $response = Http::withToken($key)
                ->withBody($torrents)
                ->post($host.'/indexes/torrents/documents');

            if ($response->failed()) {
                $this->comment('Error received while syncing torrents to Meilisearch. Aborting. Status code: '.$response->status().'. '.$response->body());

                return;
            }
        }

        $this->comment('Synced all torrents to Meilisearch in '.(now()->diffInMilliseconds($start) / 1000).' seconds.');
    }
}
