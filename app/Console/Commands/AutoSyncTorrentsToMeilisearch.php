<?php
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
    protected $signature = 'auto:sync_torrents_to_meilisearch';

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

        // Create index if it doesn't already exist
        if (Http::withToken($key)->get($host.'/indexes/torrents')->notFound()) {
            Http::withToken($key)->post($host.'/indexes', [
                'uid'        => 'torrents',
                'primaryKey' => 'id',
            ]);
        }

        $start = now();

        $maxId = DB::table('torrents')->selectRaw('MAX(id) as id')->value('id') ?? 1;
        $idsPerIteration = max(1, intdiv($maxId, 25));

        for ($id = 0; $id < $maxId; $id += $idsPerIteration) {
            $torrents = DB::table('torrents')
                ->selectRaw(Torrent::SEARCHABLE)
                ->where('id', '>', $id)
                ->where('id', '<=', $id + $idsPerIteration)
                ->value('searchable');

            Http::withToken($key)
                ->withBody($torrents)
                ->post($host.'/indexes/torrents/documents');
        }

        $this->comment('Synced all torrents to Meilisearch in '.(now()->diffInMilliseconds($start) / 1000).' seconds.');
    }
}
