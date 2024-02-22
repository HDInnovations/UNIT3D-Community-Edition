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
use Illuminate\Console\Command;
use Exception;

/**
 * @see \Tests\Todo\Unit\Console\Commands\AutoNerdStatTest
 */
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
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('chat.nerd_bot')) {
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

            $message = match ($stats) {
                'birthday' => config('other.title').' Birthday Is [b]'.config('other.birthdate').'[/b]!',
                'logins'   => 'In The Last 24 Hours [color=#93c47d][b]'.User::whereNotNull('last_login')->where('last_login', '>', now()->subDay())->count().'[/b][/color] Unique Users Have Logged Into '.config('other.title').'!',
                'uploads'  => 'In The Last 24 Hours [color=#93c47d][b]'.Torrent::where('created_at', '>', now()->subDay())->count().'[/b][/color] Torrents Have Been Uploaded To '.config('other.title').'!',
                'users'    => 'In The Last 24 Hours [color=#93c47d][b]'.User::where('created_at', '>', now()->subDay())->count().'[/b][/color] Users Have Registered To '.config('other.title').'!',
                'fl25'     => 'There Are Currently [color=#93c47d][b]'.Torrent::where('free', '=', 25)->count().'[/b][/color] 25% Freeleech Torrents On '.config('other.title').'!',
                'fl50'     => 'There Are Currently [color=#93c47d][b]'.Torrent::where('free', '=', 50)->count().'[/b][/color] 50% Freeleech Torrents On '.config('other.title').'!',
                'fl75'     => 'There Are Currently [color=#93c47d][b]'.Torrent::where('free', '=', 75)->count().'[/b][/color] 75% Freeleech Torrents On '.config('other.title').'!',
                'fl100'    => 'There Are Currently [color=#93c47d][b]'.Torrent::where('free', '=', 100)->count().'[/b][/color] 100% Freeleech Torrents On '.config('other.title').'!',
                'du'       => 'There Are Currently [color=#93c47d][b]'.Torrent::where('doubleup', '=', 1)->count().'[/b][/color] Double Upload Torrents On '.config('other.title').'!',
                'peers'    => 'Currently There Are [color=#93c47d][b]'.Peer::where('active', '=', 1)->count().'[/b][/color] Peers On '.config('other.title').'!',
                'bans'     => 'In The Last 24 Hours [color=#dd7e6b][b]'.Ban::whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', now()->subDay())->count().'[/b][/color] Users Have Been Banned From '.config('other.title').'!',
                'warnings' => 'In The Last 24 Hours [color=#dd7e6b][b]'.Warning::where('created_at', '>', now()->subDay())->count().'[/b][/color] Hit and Run Warnings Have Been Issued On '.config('other.title').'!',
                'king'     => config('other.title').' Is King!',
                default    => 'Nerd Stat Error!',
            };

            // Auto Shout Nerd Stat
            $this->chatRepository->systemMessage($message);
        }

        $this->comment('Automated Nerd Stat Command Complete');
    }
}
