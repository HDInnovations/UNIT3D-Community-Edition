<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Ban;
use App\Peer;
use App\User;
use App\Torrent;
use App\Warning;
use Illuminate\Console\Command;
use App\Repositories\ChatRepository;

class AutoNerdStat extends Command
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
    protected $signature = 'auto:nerdstat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Posts Daily Nerd Stat To Shoutbox';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('chat.nerd_bot') == true) {
            // Current Timestamp
            $current = today();
            // Site Birthday
            $bday = config('other.birthdate');
            // Logins Count Last 24hours
            $logins = User::whereNotNull('last_login')->where('last_login', '>', $current->subDay())->count();
            // Torrents Uploaded Count Last 24hours
            $uploads = Torrent::where('created_at', '>', $current->subDay())->count();
            // New Users Count Last 24hours
            $users = User::where('created_at', '>', $current->subDay())->count();
            // Top Banker
            $banker = User::latest('seedbonus')->first();
            // Most Snatched Torrent
            $snatched = Torrent::latest('times_completed')->first();
            // Most Seeded Torrent
            $seeders = Torrent::latest('seeders')->first();
            // Most Leeched Torrent
            $leechers = Torrent::latest('leechers')->first();
            // FL Torrents
            $fl = Torrent::where('free', '=', 1)->count();
            // DU Torrents
            $du = Torrent::where('doubleup', '=', 1)->count();
            // Peers Count
            $peers = Peer::count();
            // New User Bans Count Last 24hours
            $bans = Ban::whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', $current->subDay())->count();
            // Hit and Run Warning Issued In Last 24hours
            $warnings = Warning::where('created_at', '>', $current->subDay())->count();

            // Select A Random Nerd Stat
            $statArray = [
                "In The Last 24 Hours {$logins} Unique Users Have Logged Into ".config('other.title').'!',
                "In The Last 24 Hours {$uploads} Torrents Have Been Uploaded To ".config('other.title').'!',
                "In The Last 24 Hours {$users} Users Have Registered To ".config('other.title').'!',
                "There Are Currently {$fl} Freeleech Torrents On ".config('other.title').'!',
                "There Are Currently {$du} DoubleUpload Torrents On ".config('other.title').'!',
                "Currently {$seeders->name} Is The Best Seeded Torrent On ".config('other.title').'!',
                "Currently {$leechers->name} Is The Most Leeched Torrent On ".config('other.title').'!',
                "Currently {$snatched->name} Is The Most Snatched Torrent On ".config('other.title').'!',
                "Currently {$banker->username} Is The Top BON Holder On ".config('other.title').'!',
                "Currently There Is {$peers} Peers On ".config('other.title').'!',
                "In The Last 24 Hours {$bans} Users Have Been Banned From ".config('other.title').'!',
                "In The Last 24 Hours {$warnings} Hit and Run Warnings Have Been Issued On  ".config('other.title').'!',
                config('other.title')." Birthday Is {$bday}!",
                config('other.title').' Is King!',
            ];
            $selected = mt_rand(0, count($statArray) - 1);

            // Auto Shout Nerd Stat
            $this->chat->systemMessage(":nerd: [b][color=#c09fe0]NerdBot[/color][/b] : {$statArray[$selected]}");
        }
    }
}
