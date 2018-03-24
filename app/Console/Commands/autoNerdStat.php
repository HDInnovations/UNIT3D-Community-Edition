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

use Illuminate\Console\Command;
use App\Shoutbox;
use App\User;
use App\Torrent;
use Carbon\Carbon;

class autoNerdStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoNerdStat';

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
        // Current Timestamp
        $current = Carbon::now();
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
        $fl = Torrent::where('free', 1)->count();
        // DU Torrents
        $du = Torrent::where('doubleup', 1)->count();

        // Select A Random Nerd Stat
        $statArray = ["In The Last 24 Hours " . $logins . " Unique Users Have Logged Into " . config('other.title') . "!",
            "In The Last 24 Hours " . $uploads . " Torrents Have Been Uploaded To " . config('other.title') . "!",
            "In The Last 24 Hours " . $users . " Users Have Registered To " . config('other.title') . "!",
            "There Are Currently " . $fl . " Freeleech Torrents On " . config('other.title') . "!",
            "There Are Currently " . $du . " DoubleUpload Torrents On " . config('other.title') . "!",
            "Currently " . $seeders->name . " Is The Best Seeded Torrent On " . config('other.title') . "!",
            "Currently " . $leechers->name . " Is The Most Leeched Torrent On " . config('other.title') . "!",
            "Currently " . $snatched->name . " Is The Most Snatched Torrent On " . config('other.title') . "!",
            "Currently " . $banker->username . " Is The Top BON Holder On " . config('other.title') . "!",
            config('other.title') . " Birthdate Is " . $bday . "!"
        ];
        $selected = mt_rand(0, count($statArray) - 1);

        // Auto Shout Nerd Stat
        Shoutbox::create(['user' => "2", 'mentions' => "2", 'message' => ":nerd: [b]Random Nerd Stat:[/b] " . $statArray[$selected] . " :nerd:"]);
        cache()->forget('shoutbox_messages');
    }
}
