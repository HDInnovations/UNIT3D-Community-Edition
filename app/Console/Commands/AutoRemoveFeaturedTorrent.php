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

use App\Models\FeaturedTorrent;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoRemoveFeaturedTorrent extends Command
{
    /**
     * @var ChatRepository
     */
    private $chat;

    public function __construct(ChatRepository $chat)
    {
        parent::__construct();

        $this->chat = $chat;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remove_featured_torrent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes Featured Torrents If Expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current = Carbon::now();
        $featured_torrents = FeaturedTorrent::where('created_at', '<', $current->copy()->subDays(7)->toDateTimeString())->get();

        foreach ($featured_torrents as $featured_torrent) {
            // Find The Torrent
            $torrent = Torrent::where('featured', '=', 1)->where('id', '=', $featured_torrent->torrent_id)->first();
            $torrent->free = 0;
            $torrent->doubleup = 0;
            $torrent->featured = 0;
            $torrent->save();

            // Auto Announce Featured Expired
            $appurl = config('app.url');

            $this->chat->systemMessage(
                "Ladies and Gents, [url={$appurl}/torrents/{$torrent->id}]{$torrent->name}[/url] is no longer featured. :poop:"
            );

            // Delete The Record From DB
            $featured_torrent->delete();
        }
    }
}
