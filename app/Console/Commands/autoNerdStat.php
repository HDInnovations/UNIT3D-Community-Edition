<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Shoutbox;
use App\User;
use App\Torrent;

use Carbon\Carbon;
use Cache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

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
        $bday = Carbon::create(2017, 4, 1, 0);
        // Logins Count Last 24hours
        $logins = User::whereNotNull('last_login')->where('last_login', '>', $current->subDay())->count();
        // Torrents Uploaded Count Last 24hours
        $uploads = Torrent::where('created_at', '>', $current->subDay())->count();
        // New Users Count Last 24hours
        $users = User::where('created_at', '>', $current->subDay())->count();
        // Top Banker
        $banker = User::orderBy('seedbonus', 'DESC')->first();
        // Most Snatched Torrent
        $snatched = Torrent::orderBy('times_completed', 'DESC')->first();
        // Most Seeded Torrent
        $seeders = Torrent::orderBy('seeders', 'DESC')->first();
        // Most Leeched Torrent
        $leechers = Torrent::orderBy('leechers', 'DESC')->first();
        // FL Torrents
        $fl = Torrent::where('free', '=', 1)->count();
        // DU Torrents
        $du = Torrent::where('doubleup', '=', 1)->count();

        // Select A Random Nerd Stat
        $statArray = ["In The Last 24 Hours " . $logins . " Unique Users Have Logged Into " . Config::get('other.title') . "!",
            "In The Last 24 Hours " . $uploads . " Torrents Have Been Uploaded To " . Config::get('other.title') . "!",
            "In The Last 24 Hours " . $users . " Users Have Registered To " . Config::get('other.title') . "!",
            "There Are Currently " . $fl . " Freeleech Torrents On " . Config::get('other.title') . "!",
            "There Are Currently " . $du . " DoubleUpload Torrents On " . Config::get('other.title') . "!",
            "Currently " . $seeders->name . " Is The Best Seeded Torrent On " . Config::get('other.title') . "!",
            "Currently " . $leechers->name . " Is The Most Leeched Torrent On " . Config::get('other.title') . "!",
            "Currently " . $snatched->name . " Is The Most Snatched Torrent On " . Config::get('other.title') . "!",
            "Currently " . $banker->username . " Is The Top BON Holder On " . Config::get('other.title') . "!",
            Config::get('other.title') . " Birthdate Is " . $bday->toFormattedDateString() . "!"
        ];
        $selected = mt_rand(0, count($statArray) - 1);

        // Auto Shout Nerd Stat
        Shoutbox::create(['user' => "2", 'mentions' => "2", 'message' => ":nerd: [b]Random Nerd Stat:[/b] " . $statArray[$selected] . " :nerd:"]);
        Cache::forget('shoutbox_messages');
    }
}
