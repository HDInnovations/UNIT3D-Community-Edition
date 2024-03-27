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

use App\Models\Resurrection;
use App\Models\History;
use App\Models\PrivateMessage;
use App\Models\Torrent;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Unit\Console\Commands\AutoGraveyardTest
 */
class AutoRewardResurrection extends Command
{
    /**
     * AutoRewardResurrection's Constructor.
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
    protected $signature = 'auto:reward_resurrection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Hands Out Rewards For Successful Resurrections';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Resurrection::where('rewarded', '!=', 1)->oldest()->get() as $resurrection) {
            $user = User::find($resurrection->user_id);

            $torrent = Torrent::find($resurrection->torrent_id);

            if (isset($user, $torrent)) {
                $history = History::where('torrent_id', '=', $torrent->id)
                    ->where('user_id', '=', $user->id)
                    ->where('seedtime', '>=', $resurrection->seedtime)
                    ->first();
            }

            if (isset($history)) {
                $resurrection->rewarded = true;
                $resurrection->save();

                $user->fl_tokens += config('graveyard.reward');
                $user->save();

                // Auto Shout
                $appurl = config('app.url');

                $this->chatRepository->systemMessage(
                    sprintf('Ladies and Gents, [url=%s/users/%s]%s[/url] has successfully resurrected [url=%s/torrents/%s]%s[/url].', $appurl, $user->username, $user->username, $appurl, $torrent->id, $torrent->name)
                );

                // Bump Torrent With FL
                $torrentUrl = href_torrent($torrent);
                $torrent->bumped_at = Carbon::now();
                $torrent->free = 100;
                $torrent->fl_until = Carbon::now()->addDays(3);
                $this->chatRepository->systemMessage(
                    sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted 100%% FreeLeech for 3 days and has been bumped to the top.', $torrentUrl, $torrent->name)
                );
                $torrent->save();

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                // Send Private Message
                $pm = new PrivateMessage();
                $pm->sender_id = 1;
                $pm->receiver_id = $user->id;
                $pm->subject = 'Successful Graveyard Resurrection';
                $pm->message = sprintf('You have successfully resurrected [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] ! Thank you for bringing a torrent back from the dead! Enjoy the freeleech tokens!
                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
                $pm->save();
            }
        }

        $this->comment('Automated Reward Resurrections Command Complete');
    }
}
