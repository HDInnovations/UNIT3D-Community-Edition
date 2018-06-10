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

namespace App\Console\Commands;

use App\Repositories\ChatRepository;
use Illuminate\Console\Command;
use App\Torrent;
use App\History;
use App\Graveyard;
use App\PrivateMessage;
use App\User;
use App\Message;

class autoGraveyard extends Command
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
    protected $signature = 'autoGraveyard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Checks Graveyard Records For Succesful Ressurections';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rewardable = Graveyard::where('rewarded', '!=', 1)->get();

        foreach ($rewardable as $reward) {
            $user = User::where('id', $reward->user_id)->first();
            $torrent = Torrent::where('id', $reward->torrent_id)->first();
            $history = History::where('info_hash', $torrent->info_hash)->where('user_id', $user->id)->first();

            if ($history && $history->seedtime >= $reward->seedtime) {
                $reward->rewarded = 1;
                $reward->save();

                $user->fl_tokens += config('graveyard.reward');
                $user->save();

                // Auto Shout
                $appurl = config('app.url');

                $this->chat->systemMessage(
                    "Ladies and Gents, [url={$appurl}/{$user->username}.{$user->id}]{$user->username}[/url] has successfully resurrected [url={$appurl}/torrents/{$torrent->slug}.{$torrent->id}]{$torrent->name}[/url]. :zombie:"
                );

                // Send Private Message
                $pm = new PrivateMessage;
                $pm->sender_id = 1;
                $pm->receiver_id = $user->id;
                $pm->subject = "Successful Graveyard Resurrection";
                $pm->message = "You have successfully resurrected [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url] :zombie: ! Thank you for bringing a torrent back from the dead! Enjoy the freeleech tokens!
                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]";
                $pm->save();
            }
        }
    }
}
