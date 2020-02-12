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

namespace App\Bots;

use App\Events\Chatter;
use App\Http\Resources\UserAudibleResource;
use App\Http\Resources\UserEchoResource;
use App\Models\Ban;
use App\Models\Bot;
use App\Models\BotTransaction;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Models\Warning;
use App\Repositories\ChatRepository;
use Carbon\Carbon;

class NerdBot
{
    private $bot;

    private $chat;

    private $target;

    private $type;

    private $message;

    private $targeted;

    private $log;

    private $expiresAt;

    private $current;

    /**
     * NerdBot Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $bot = Bot::where('id', '=', '2')->firstOrFail();
        $this->chat = $chat;
        $this->bot = $bot;
        $this->expiresAt = Carbon::now()->addMinutes(60);
        $this->current = Carbon::now();
    }

    /**
     * Replace Vars.
     *
     * @param $output
     *
     * @return mixed
     */
    public function replaceVars($output)
    {
        $output = str_replace('{me}', $this->bot->name, $output);
        $output = str_replace('{command}', $this->bot->command, $output);
        if (strstr($output, '{bots}')) {
            $bot_help = '';
            $bots = Bot::where('active', '=', 1)->where('id', '!=', $this->bot->id)->orderBy('position', 'asc')->get();
            foreach ($bots as $bot) {
                $bot_help .= '( ! | / | @)'.$bot->command.' help triggers help file for '.$bot->name."\n";
            }
            $output = str_replace('{bots}', $bot_help, $output);
        }

        return $output;
    }

    /**
     * Get Banker.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getBanker($duration = 'default')
    {
        $banker = cache()->get('nerdbot-banker');
        if (!$banker || $banker == null) {
            $banker = User::latest('seedbonus')->first();
            cache()->put('nerdbot-banker', $banker, $this->expiresAt);
        }

        return "Currently [url=/users/{$banker->username}]{$banker->username}[/url] Is The Top BON Holder On ".config('other.title').'!';
    }

    /**
     * Get Snatched.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getSnatched($duration = 'default')
    {
        $snatched = cache()->get('nerdbot-snatched');
        if (!$snatched || $snatched == null) {
            $snatched = Torrent::latest('times_completed')->first();
            cache()->put('nerdbot-snatched', $snatched, $this->expiresAt);
        }

        return "Currently [url=/torrents/{$snatched->id}]{$snatched->name}[/url] Is The Most Snatched Torrent On ".config('other.title').'!';
    }

    /**
     * Get Leeched.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getLeeched($duration = 'default')
    {
        $leeched = cache()->get('nerdbot-leeched');
        if (!$leeched || $leeched == null) {
            $leeched = Torrent::latest('leechers')->first();
            cache()->put('nerdbot-leeched', $leeched, $this->expiresAt);
        }

        return "Currently [url=/torrents/{$leeched->id}]{$leeched->name}[/url] Is The Most Leeched Torrent On ".config('other.title').'!';
    }

    /**
     * Get Seeded.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getSeeded($duration = 'default')
    {
        $seeded = cache()->get('nerdbot-seeded');
        if (!$seeded || $seeded == null) {
            $seeded = Torrent::latest('seeders')->first();
            cache()->put('nerdbot-seeded', $seeded, $this->expiresAt);
        }

        return "Currently [url=/torrents/{$seeded->id}]{$seeded->name}[/url] Is The Most Seeded Torrent On ".config('other.title').'!';
    }

    /**
     * Get FL.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getFreeleech($duration = 'default')
    {
        $fl = cache()->get('nerdbot-fl');
        if (!$fl || $fl == null) {
            $fl = Torrent::where('free', '=', 1)->count();
            cache()->put('nerdbot-fl', $fl, $this->expiresAt);
        }

        return "There Are Currently {$fl} Freeleech Torrents On ".config('other.title').'!';
    }

    /**
     * Get DU.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getDoubleUpload($duration = 'default')
    {
        $du = cache()->get('nerdbot-doubleup');
        if (!$du || $du == null) {
            $du = Torrent::where('doubleup', '=', 1)->count();
            cache()->put('nerdbot-doubleup', $du, $this->expiresAt);
        }

        return "There Are Currently {$du} Double Upload Torrents On ".config('other.title').'!';
    }

    /**
     * Get Peers.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getPeers($duration = 'default')
    {
        $peers = cache()->get('nerdbot-peers');
        if (!$peers || $peers == null) {
            $peers = Peer::count();
            cache()->put('nerdbot-peers', $peers, $this->expiresAt);
        }

        return "Currently There Are {$peers} Peers On ".config('other.title').'!';
    }

    /**
     * Get Bans.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getBans($duration = 'default')
    {
        $bans = cache()->get('nerdbot-bans');
        if (!$bans || $bans == null) {
            $bans = Ban::whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', $this->current->subDay())->count();
            cache()->put('nerdbot-bans', $bans, $this->expiresAt);
        }

        return "In The Last 24 Hours {$bans} Users Have Been Banned From ".config('other.title').'!';
    }

    /**
     * Get Warnings.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getWarnings($duration = 'default')
    {
        $warnings = cache()->get('nerdbot-warnings');
        if (!$warnings || $warnings == null) {
            $warnings = Warning::where('created_at', '>', $this->current->subDay())->count();
            cache()->put('nerdbot-warnings', $warnings, $this->expiresAt);
        }

        return "In The Last 24 Hours {$warnings} Hit and Run Warnings Have Been Issued On ".config('other.title').'!';
    }

    /**
     * Get Uploads.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getUploads($duration = 'default')
    {
        $uploads = cache()->get('nerdbot-uploads');
        if (!$uploads || $uploads == null) {
            $uploads = Torrent::where('created_at', '>', $this->current->subDay())->count();
            cache()->put('nerdbot-uploads', $uploads, $this->expiresAt);
        }

        return "In The Last 24 Hours {$uploads} Torrents Have Been Uploaded To ".config('other.title').'!';
    }

    /**
     * Get Logins.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getLogins($duration = 'default')
    {
        $logins = cache()->get('nerdbot-logins');
        if (!$logins || $logins == null) {
            $logins = User::whereNotNull('last_login')->where('last_login', '>', $this->current->subDay())->count();
            cache()->put('nerdbot-logins', $logins, $this->expiresAt);
        }

        return "In The Last 24 Hours {$logins} Unique Users Have Logged Into ".config('other.title').'!';
    }

    /**
     * Get Registrations.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getRegistrations($duration = 'default')
    {
        $registrations = cache()->get('nerdbot-users');
        if (!$registrations || $registrations == null) {
            $users = User::where('created_at', '>', $this->current->subDay())->count();
            cache()->put('nerdbot-users', $users, $this->expiresAt);
        }

        return "In The Last 24 Hours {$users} Users Have Registered To ".config('other.title').'!';
    }

    /**
     * Get Bot Donations.
     *
     * @param string $duration
     *
     * @return string
     */
    public function getDonations($duration = 'default')
    {
        $donations = cache()->get('nerdbot-donations');
        if (!$donations || $donations == null) {
            $donations = BotTransaction::with('user', 'bot')->where('to_bot', '=', 1)->latest()->limit(10)->get();
            cache()->put('nerdbot-donations', $donations, $this->expiresAt);
        }
        $donation_dump = '';
        $i = 1;
        foreach ($donations as $donation) {
            $donation_dump .= '#'.$i.'. '.$donation->user->username.' sent '.$donation->bot->name.' '.$donation->cost.' '.$donation->forHumans().".\n";
            $i++;
        }

        return "The Most Recent Donations To All Bots Are As Follows:\n\n".trim($donation_dump);
    }

    /**
     * Get Help.
     */
    public function getHelp()
    {
        return $this->replaceVars($this->bot->help);
    }

    /**
     * Get King.
     */
    public function getKing()
    {
        return config('other.title').' Is King!';
    }

    /**
     * Send Bot Donation.
     *
     * @param int    $amount
     * @param string $note
     *
     * @return string
     */
    public function putDonate($amount = 0, $note = '')
    {
        $output = implode(' ', $note);
        $v = validator(['bot_id' => $this->bot->id, 'amount'=> $amount, 'note'=> $output], [
            'bot_id'   => 'required|exists:bots,id|max:999',
            'amount'   => "required|numeric|min:1|max:{$this->target->seedbonus}",
            'note'     => 'required|string',
        ]);
        if ($v->passes()) {
            $value = $amount;
            $this->bot->seedbonus += $value;
            $this->bot->save();

            $this->target->seedbonus -= $value;
            $this->target->save();

            $transaction = new BotTransaction();
            $transaction->type = 'bon';
            $transaction->cost = $value;
            $transaction->user_id = $this->target->id;
            $transaction->bot_id = $this->bot->id;
            $transaction->to_bot = 1;
            $transaction->comment = $output;
            $transaction->save();

            $donations = BotTransaction::with('user', 'bot')->where('bot_id', '=', $this->bot->id)->where('to_bot', '=', 1)->latest()->limit(10)->get();
            cache()->put('casinobot-donations', $donations, $this->expiresAt);

            return 'Your donation to '.$this->bot->name.' for '.$amount.' BON has been sent!';
        }

        return 'Your donation to '.$output.' could not be sent.';
    }

    /**
     * Process Message.
     *
     * @param $type
     * @param User   $target
     * @param string $message
     * @param int    $targeted
     *
     * @return bool
     */
    public function process($type, User $target, $message = '', $targeted = 0)
    {
        $this->target = $target;
        if ($type == 'message') {
            $x = 0;
            $y = 1;
            $z = 2;
        } else {
            $x = 1;
            $y = 2;
            $z = 3;
        }

        if ($message == '') {
            $log = '';
        } else {
            $log = 'All '.$this->bot->name.' commands must be a private message or begin with /'.$this->bot->command.' or !'.$this->bot->command.'. Need help? Type /'.$this->bot->command.' help and you shall be helped.';
        }
        $command = @explode(' ', $message);

        $wildcard = null;
        $params = null;
        if (array_key_exists($y, $command)) {
            $params = $command[$y];
        }

        if ($params != null) {
            $clone = $command;
            array_shift($clone);
            array_shift($clone);
            array_shift($clone);
            $wildcard = $clone;
        }

        if (array_key_exists($x, $command)) {
            if ($command[$x] == 'banker') {
                $log = $this->getBanker($params);
            }
            if ($command[$x] == 'bans') {
                $log = $this->getBans($params);
            }
            if ($command[$x] == 'donations') {
                $log = $this->getDonations($params);
            }
            if ($command[$x] == 'donate') {
                $log = $this->putDonate($params, $wildcard);
            }
            if ($command[$x] == 'doubleupload') {
                $log = $this->getDoubleUpload($params);
            }
            if ($command[$x] == 'freeleech') {
                $log = $this->getFreeleech($params);
            }
            if ($command[$x] == 'help') {
                $log = $this->getHelp();
            }
            if ($command[$x] == 'king') {
                $log = $this->getKing();
            }
            if ($command[$x] == 'logins') {
                $log = $this->getLogins($params);
            }
            if ($command[$x] == 'peers') {
                $log = $this->getPeers($params);
            }
            if ($command[$x] == 'registrations') {
                $log = $this->getRegistrations($params);
            }
            if ($command[$x] == 'uploads') {
                $log = $this->getUploads($params);
            }
            if ($command[$x] == 'warnings') {
                $log = $this->getWarnings($params);
            }
            if ($command[$x] == 'seeded') {
                $log = $this->getSeeded($params);
            }
            if ($command[$x] == 'leeched') {
                $log = $this->getLeeched($params);
            }
            if ($command[$x] == 'snatched') {
                $log = $this->getSnatched($params);
            }
        }
        $this->targeted = $targeted;
        $this->type = $type;
        $this->message = $message;
        $this->log = $log;

        return $this->pm();
    }

    /**
     * Output Message.
     */
    public function pm()
    {
        $type = $this->type;
        $target = $this->target;
        $txt = $this->log;
        $message = $this->message;
        $targeted = $this->targeted;

        if ($targeted) {
            // future holder
        }
        if ($type == 'message' || $type == 'private') {
            $receiver_dirty = 0;
            $receiver_echoes = cache()->get('user-echoes'.$target->id);
            if (!$receiver_echoes || !is_array($receiver_echoes) || count($receiver_echoes) < 1) {
                $receiver_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$target->id])->get();
            }
            $receiver_listening = false;
            foreach ($receiver_echoes as $se => $receiver_echo) {
                if ($receiver_echo['bot_id'] == $this->bot->id) {
                    $receiver_listening = true;
                }
            }
            if (!$receiver_listening) {
                $receiver_port = new UserEcho();
                $receiver_port->user_id = $target->id;
                $receiver_port->bot_id = $this->bot->id;
                $receiver_port->save();
                $receiver_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$target->id])->get();
                $receiver_dirty = 1;
            }
            if ($receiver_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$target->id, $receiver_echoes, $expiresAt);
                event(new Chatter('echo', $target->id, UserEchoResource::collection($receiver_echoes)));
            }
            $receiver_dirty = 0;
            $receiver_audibles = cache()->get('user-audibles'.$target->id);
            if (!$receiver_audibles || !is_array($receiver_audibles) || count($receiver_audibles) < 1) {
                $receiver_audibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$target->id])->get();
            }
            $receiver_listening = false;
            foreach ($receiver_audibles as $se => $receiver_echo) {
                if ($receiver_echo['bot_id'] == $this->bot->id) {
                    $receiver_listening = true;
                }
            }
            if (!$receiver_listening) {
                $receiver_port = new UserAudible();
                $receiver_port->user_id = $target->id;
                $receiver_port->bot_id = $this->bot->id;
                $receiver_port->save();
                $receiver_audibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$target->id])->get();
                $receiver_dirty = 1;
            }
            if ($receiver_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$target->id, $receiver_audibles, $expiresAt);
                event(new Chatter('audible', $target->id, UserAudibleResource::collection($receiver_audibles)));
            }
            if ($txt != '') {
                $room_id = 0;
                $message = $this->chat->privateMessage($target->id, $room_id, $message, 1, $this->bot->id);
                $message = $this->chat->privateMessage(1, $room_id, $txt, $target->id, $this->bot->id);
            }

            return response('success');
        }

        if ($type == 'echo') {
            if ($txt != '') {
                $room_id = 0;
                $message = $this->chat->botMessage($this->bot->id, $room_id, $txt, $target->id);
            }

            return response('success');
        } elseif ($type == 'public') {
            if ($txt != '') {
                $dumproom = $this->chat->message($target->id, $target->chatroom->id, $message, null, null);
                $dumproom = $this->chat->message(1, $target->chatroom->id, $txt, null, $this->bot->id);
            }

            return response('success');
        }

        return true;
    }
}
