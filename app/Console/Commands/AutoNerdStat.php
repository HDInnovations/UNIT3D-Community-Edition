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

use App\Models\Ban;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Warning;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
            $current = Carbon::now();

            // Cache Expires Timestamp
            $expiresAt = $current->addMinutes(60);

            // Site Birthday
            $bday = config('other.birthdate');

            // Logins Count Last 24hours
            $logins = User::whereNotNull('last_login')->where('last_login', '>', $current->subDay())->count();
            cache()->put('nerdbot-logins', $logins, $expiresAt);

            // Torrents Uploaded Count Last 24hours
            $uploads = Torrent::where('created_at', '>', $current->subDay())->count();
            cache()->put('nerdbot-uploads', $uploads, $expiresAt);

            // New Users Count Last 24hours
            $users = User::where('created_at', '>', $current->subDay())->count();
            cache()->put('nerdbot-users', $users, $expiresAt);

            // Top Banker
            $banker = User::latest('seedbonus')->first();
            cache()->put('nerdbot-banker', $banker, $expiresAt);

            // Most Snatched Torrent
            $snatched = Torrent::latest('times_completed')->first();
            cache()->put('nerdbot-snatched', $snatched, $expiresAt);

            // Most Seeded Torrent
            $seeded = Torrent::latest('seeders')->first();
            cache()->put('nerdbot-seeded', $seeded, $expiresAt);

            // Most Leeched Torrent
            $leeched = Torrent::latest('leechers')->first();
            cache()->put('nerdbot-leeched', $leeched, $expiresAt);

            // FL Torrents
            $fl = Torrent::where('free', '=', 1)->count();
            cache()->put('nerdbot-fl', $fl, $expiresAt);

            // DU Torrents
            $du = Torrent::where('doubleup', '=', 1)->count();
            cache()->put('nerdbot-doubleup', $du, $expiresAt);

            // Peers Count
            $peers = Peer::count();
            cache()->put('nerdbot-peers', $peers, $expiresAt);

            // New User Bans Count Last 24hours
            $bans = Ban::whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', $current->subDay())->count();
            cache()->put('nerdbot-bans', $bans, $expiresAt);

            // Hit and Run Warning Issued In Last 24hours
            $warnings = Warning::where('created_at', '>', $current->subDay())->count();
            cache()->put('nerdbot-warnings', $warnings, $expiresAt);

            // URL Helpers
            $banker_url = hrefProfile($banker);
            $seeded_url = hrefTorrent($seeded);
            $leeched_url = hrefTorrent($leeched);
            $snatched_url = hrefTorrent($snatched);

            // Select A Random Nerd Stat
            $statArray = [
                sprintf('In The Last 24 Hours [color=#93c47d][b]%s[/b][/color] Unique Users Have Logged Into ', $logins).config('other.title').'!',
                sprintf('In The Last 24 Hours [color=#93c47d][b]%s[/b][/color] Torrents Have Been Uploaded To ', $uploads).config('other.title').'!',
                sprintf('In The Last 24 Hours [color=#93c47d][b]%s[/b][/color] Users Have Registered To ', $users).config('other.title').'!',
                sprintf('There Are Currently [color=#93c47d][b]%s[/b][/color] Freeleech Torrents On ', $fl).config('other.title').'!',
                sprintf('There Are Currently [color=#93c47d][b]%s[/b][/color] Double Upload Torrents On ', $du).config('other.title').'!',
                sprintf('Currently [url=%s]%s[/url] Is The Best Seeded Torrent On ', $seeded_url, $seeded->name).config('other.title').'!',
                sprintf('Currently [url=%s]%s[/url] Is The Most Leeched Torrent On ', $leeched_url, $leeched->name).config('other.title').'!',
                sprintf('Currently [url=%s]%s[/url] Is The Most Snatched Torrent On ', $snatched_url, $snatched->name).config('other.title').'!',
                sprintf('Currently [url=%s]%s[/url] Is The Top BON Holder On ', $banker_url, $banker->username).config('other.title').'!',
                sprintf('Currently There Are [color=#93c47d][b]%s[/b][/color] Peers On ', $peers).config('other.title').'!',
                sprintf('In The Last 24 Hours [color=#dd7e6b][b]%s[/b][/color] Users Have Been Banned From ', $bans).config('other.title').'!',
                sprintf('In The Last 24 Hours [color=#dd7e6b][b]%s[/b][/color] Hit and Run Warnings Have Been Issued On ', $warnings).config('other.title').'!',
                config('other.title').sprintf(' Birthday Is [b]%s[/b]!', $bday),
                config('other.title').' Is King!',
            ];
            $selected = mt_rand(0, count($statArray) - 1);

            // Auto Shout Nerd Stat
            $this->chat->systemMessage(sprintf('%s', $statArray[$selected]), 2);
        }
        $this->comment('Automated Nerd Stat Command Complete');
    }
}
