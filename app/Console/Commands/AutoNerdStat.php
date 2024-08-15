<?php

declare(strict_types=1);

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

use App\Repositories\ChatRepository;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class AutoNerdStat extends Command
{
    /**
     * AutoNerdStat Constructor.
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
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        // Check if the nerd bot is enabled in the configuration.
        if (config('chat.nerd_bot')) {
            // Define the possible stats.
            $stats = collect([
                'birthday',
                'logins',
                'uploads',
                'users',
                'fl25',
                'fl50',
                'fl75',
                'fl100',
                'du',
                'peers',
                'bans',
                'warnings',
                'king',
            ])->random();

            // Generate the message based on the selected stat.
            $message = match ($stats) {
                'birthday' => config('other.title').' Birthday Is [b]'.config('other.birthdate').'[/b]!',
                'logins'   => 'In The Last 24 Hours [color=#93c47d][b]'.DB::table('users')->whereNotNull('last_login')->where('last_login', '>', now()->subDay())->count().'[/b][/color] Unique Users Have Logged Into '.config('other.title').'!',
                'uploads'  => 'In The Last 24 Hours [color=#93c47d][b]'.DB::table('torrents')->where('created_at', '>', now()->subDay())->count().'[/b][/color] Torrents Have Been Uploaded To '.config('other.title').'!',
                'users'    => 'In The Last 24 Hours [color=#93c47d][b]'.DB::table('users')->where('created_at', '>', now()->subDay())->count().'[/b][/color] Users Have Registered To '.config('other.title').'!',
                'fl25'     => 'There Are Currently [color=#93c47d][b]'.DB::table('torrents')->where('free', '=', 25)->count().'[/b][/color] 25% Freeleech Torrents On '.config('other.title').'!',
                'fl50'     => 'There Are Currently [color=#93c47d][b]'.DB::table('torrents')->where('free', '=', 50)->count().'[/b][/color] 50% Freeleech Torrents On '.config('other.title').'!',
                'fl75'     => 'There Are Currently [color=#93c47d][b]'.DB::table('torrents')->where('free', '=', 75)->count().'[/b][/color] 75% Freeleech Torrents On '.config('other.title').'!',
                'fl100'    => 'There Are Currently [color=#93c47d][b]'.DB::table('torrents')->where('free', '=', 100)->count().'[/b][/color] 100% Freeleech Torrents On '.config('other.title').'!',
                'du'       => 'There Are Currently [color=#93c47d][b]'.DB::table('torrents')->where('doubleup', '=', 1)->count().'[/b][/color] Double Upload Torrents On '.config('other.title').'!',
                'peers'    => 'Currently There Are [color=#93c47d][b]'.DB::table('peers')->where('active', '=', 1)->count().'[/b][/color] Peers On '.config('other.title').'!',
                'bans'     => 'In The Last 24 Hours [color=#dd7e6b][b]'.DB::table('bans')->whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', now()->subDay())->count().'[/b][/color] Users Have Been Banned From '.config('other.title').'!',
                'warnings' => 'In The Last 24 Hours [color=#dd7e6b][b]'.DB::table('warnings')->where('created_at', '>', now()->subDay())->count().'[/b][/color] Hit and Run Warnings Have Been Issued On '.config('other.title').'!',
                'king'     => config('other.title').' Is King!',
                default    => 'Nerd Stat Error!',
            };

            // Post the message to the chatbox.
            $this->chatRepository->systemMessage($message);
        }

        // Output a success message to the console.
        $this->comment('Automated Nerd Stat Command Complete');
    }
}
