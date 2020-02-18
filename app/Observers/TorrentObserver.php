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

namespace App\Observers;

use App\Models\Torrent;
use Illuminate\Support\Facades\Cache;

class TorrentObserver
{
    /**
     * Handle the Torrent "created" event.
     *
     * @param \App\Models\Torrent $torrent
     *
     * @return void
     */
    public function created(Torrent $torrent)
    {
        Cache::put(sprintf('torrent.%s', $torrent->info_hash), $torrent);
    }

    /**
     * Handle the Torrent "updated" event.
     *
     * @param \App\Models\Torrent $torrent
     *
     * @return void
     */
    public function updated(Torrent $torrent)
    {
        Cache::put(sprintf('torrent.%s', $torrent->info_hash), $torrent);
    }

    /**
     * Handle the Torrent "retrieved" event.
     *
     * @param \App\Models\Torrent $torrent
     *
     * @return void
     */
    public function retrieved(Torrent $torrent)
    {
        Cache::add(sprintf('torrent.%s', $torrent->info_hash), $torrent);
    }

    /**
     * Handle the Torrent "deleted" event.
     *
     * @param \App\Models\Torrent $torrent
     *
     * @return void
     */
    public function deleted(Torrent $torrent)
    {
        Cache::forget(sprintf('torrent.%s', $torrent->info_hash));
    }

    /**
     * Handle the Torrent "restored" event.
     *
     * @param \App\Models\Torrent $torrent
     *
     * @return void
     */
    public function restored(Torrent $torrent)
    {
        Cache::put(sprintf('torrent.%s', $torrent->info_hash), $torrent);
    }
}
