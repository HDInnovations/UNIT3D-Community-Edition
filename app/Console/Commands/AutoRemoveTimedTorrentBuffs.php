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

namespace App\Console\Commands;

use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Unit\Console\Commands\AutoRemoveTimedTorrentBuffs
 */
class AutoRemoveTimedTorrentBuffs extends Command
{
    /**
     * AutoRemoveTimedTorrentBuffs Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remove_torrent_buffs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes Torrent Buffs If Expired';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $appurl = \config('app.url');

        $flTorrents = Torrent::whereNotNull('fl_until')->where('fl_until', '<', Carbon::now()->toDateTimeString())->get();

        foreach ($flTorrents as $torrent) {
            if (isset($torrent)) {
                $torrent->free = 0;
                $torrent->fl_until = null;
                $torrent->save();

                // Announce To Chat
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/%s]%s[/url] timed freeleech buff has expired.', $appurl, $torrent->id, $torrent->name)
                );
            }
        }

        $duTorrents = Torrent::whereNotNull('du_until')->where('du_until', '<', Carbon::now()->toDateTimeString())->get();

        foreach ($duTorrents as $torrent) {
            if (isset($torrent)) {
                $torrent->doubleup = 0;
                $torrent->du_until = null;
                $torrent->save();

                // Announce To Chat
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s/torrents/%s]%s[/url] timed double upload buff has expired.', $appurl, $torrent->id, $torrent->name)
                );
            }
        }

        $this->comment('Automated Removal Of Expired Torrent Buffs Command Complete');
    }
}
