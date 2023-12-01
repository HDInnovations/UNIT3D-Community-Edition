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
use Illuminate\Support\Carbon;
use Exception;

class NerdBot
{
    private Bot $bot;

    private User $target;

    private string $type;

    private string $message;

    private string $log;

    private Carbon $expiresAt;

    private Carbon $current;

    public function __construct(private readonly ChatRepository $chatRepository)
    {
        $this->bot = Bot::findOrFail(2);
        $this->expiresAt = Carbon::now()->addMinutes(60);
        $this->current = Carbon::now();
    }

    public function replaceVars(string $output): string
    {
        $output = str_replace(['{me}', '{command}'], [$this->bot->name, $this->bot->command], $output);

        if (str_contains((string) $output, '{bots}')) {
            $botHelp = '';
            $bots = Bot::where('active', '=', 1)->where('id', '!=', $this->bot->id)->oldest('position')->get();

            foreach ($bots as $bot) {
                $botHelp .= '( ! | / | @)'.$bot->command.' help triggers help file for '.$bot->name."\n";
            }

            $output = str_replace('{bots}', $botHelp, $output);
        }

        return $output;
    }

    public function getBanker(): string
    {
        $banker = cache()->remember(
            'nerdbot-banker',
            $this->expiresAt,
            fn () => User::orderByDesc('seedbonus')->first()
        );

        return sprintf('Currently [url=/users/%s]%s[/url] Is The Top BON Holder On ', $banker->username, $banker->username).config('other.title').'!';
    }

    public function getSnatched(): string
    {
        $snatched = cache()->remember(
            'nerdbot-snatched',
            $this->expiresAt,
            fn () => Torrent::orderByDesc('times_completed')->first()
        );

        return sprintf('Currently [url=/torrents/%s]%s[/url] Is The Most Snatched Torrent On ', $snatched->id, $snatched->name).config('other.title').'!';
    }

    public function getLeeched(): string
    {
        $leeched = cache()->remember(
            'nerdbot-leeched',
            $this->expiresAt,
            fn () => Torrent::orderByDesc('leechers')->first()
        );

        return sprintf('Currently [url=/torrents/%s]%s[/url] Is The Most Leeched Torrent On ', $leeched->id, $leeched->name).config('other.title').'!';
    }

    public function getSeeded(): string
    {
        $seeded = cache()->remember(
            'nerdbot-seeded',
            $this->expiresAt,
            fn () => Torrent::orderByDesc('seeders')->first()
        );

        return sprintf('Currently [url=/torrents/%s]%s[/url] Is The Most Seeded Torrent On ', $seeded->id, $seeded->name).config('other.title').'!';
    }

    public function getFreeleech(): string
    {
        $freeleech = cache()->remember(
            'nerdbot-freeleech',
            $this->expiresAt,
            fn () => Torrent::where('free', '=', 1)->count()
        );

        return sprintf('There Are Currently %s Freeleech Torrents On ', $freeleech).config('other.title').'!';
    }

    public function getDoubleUpload(): string
    {
        $doubleUpload = cache()->remember(
            'nerdbot-doubleupload',
            $this->expiresAt,
            fn () => Torrent::where('doubleup', '=', 1)->count()
        );

        return sprintf('There Are Currently %s Double Upload Torrents On ', $doubleUpload).config('other.title').'!';
    }

    public function getPeers(): string
    {
        $peers = cache()->remember(
            'nerdbot-peers',
            $this->expiresAt,
            fn () => Peer::where('active', '=', 1)->count()
        );

        return sprintf('Currently There Are %s Peers On ', $peers).config('other.title').'!';
    }

    public function getBans(): string
    {
        $bans = cache()->remember(
            'nerdbot-bans',
            $this->expiresAt,
            fn () => Ban::whereNull('unban_reason')
                ->whereNull('removed_at')
                ->where('created_at', '>', $this->current->subDay())->count()
        );

        return sprintf('In The Last 24 Hours %s Users Have Been Banned From ', $bans).config('other.title').'!';
    }

    public function getWarnings(): string
    {
        $warnings = cache()->remember(
            'nerdbot-warnings',
            $this->expiresAt,
            fn () => Warning::where('created_at', '>', $this->current->subDay())->count()
        );

        return sprintf('In The Last 24 Hours %s Hit and Run Warnings Have Been Issued On ', $warnings).config('other.title').'!';
    }

    public function getUploads(): string
    {
        $uploads = cache()->remember(
            'nerdbot-uploads',
            $this->expiresAt,
            fn () => Torrent::where('created_at', '>', $this->current->subDay())->count()
        );

        return sprintf('In The Last 24 Hours %s Torrents Have Been Uploaded To ', $uploads).config('other.title').'!';
    }

    public function getLogins(): string
    {
        $logins = cache()->remember(
            'nerdbot-logins',
            $this->expiresAt,
            fn () => User::whereNotNull('last_login')->where('last_login', '>', $this->current->subDay())->count()
        );

        return sprintf('In The Last 24 Hours %s Unique Users Have Logged Into ', $logins).config('other.title').'!';
    }

    public function getRegistrations(): string
    {
        $registrations = cache()->remember(
            'nerdbot-users',
            $this->expiresAt,
            fn () => User::where('created_at', '>', $this->current->subDay())->count()
        );

        return sprintf('In The Last 24 Hours %s Users Have Registered To ', $registrations).config('other.title').'!';
    }

    /**
     * Get Bot Donations.
     */
    public function getDonations(): string
    {
        $donations = cache()->remember(
            'nerdbot-donations',
            $this->expiresAt,
            fn () => BotTransaction::with('user', 'bot')->where('to_bot', '=', 1)->latest()->limit(10)->get()
        );

        $donationDump = '';
        $i = 1;

        foreach ($donations as $donation) {
            $donationDump .= '#'.$i.'. '.$donation->user->username.' sent '.$donation->bot->name.' '.$donation->cost.' '.$donation->forHumans().".\n";
            $i++;
        }

        return "The Most Recent Donations To All Bots Are As Follows:\n\n".trim($donationDump);
    }

    public function getHelp(): string
    {
        return $this->replaceVars($this->bot->help);
    }

    public function getKing(): string
    {
        return config('other.title').' Is King!';
    }

    /**
     * Send Bot Donation.
     *
     * @param  array<string> $note
     * @throws Exception
     */
    public function putDonate(float $amount = 0, array $note = ['']): string
    {
        $output = implode(' ', $note);
        $v = validator(['bot_id' => $this->bot->id, 'amount' => $amount, 'note' => $output], [
            'bot_id' => 'required|exists:bots,id|max:999',
            'amount' => sprintf('required|numeric|min:1|max:%s', $this->target->seedbonus),
            'note'   => 'required|string',
        ]);

        if ($v->passes()) {
            $value = $amount;
            $this->bot->seedbonus += $value;
            $this->bot->save();

            $this->target->seedbonus -= $value;
            $this->target->save();

            $botTransaction = new BotTransaction();
            $botTransaction->type = 'bon';
            $botTransaction->cost = $value;
            $botTransaction->user_id = $this->target->id;
            $botTransaction->bot_id = $this->bot->id;
            $botTransaction->to_bot = true;
            $botTransaction->comment = $output;
            $botTransaction->save();

            $donations = BotTransaction::with('user', 'bot')->where('bot_id', '=', $this->bot->id)->where('to_bot', '=', 1)->latest()->limit(10)->get();
            cache()->put('casinobot-donations', $donations, $this->expiresAt);

            return 'Your donation to '.$this->bot->name.' for '.$amount.' BON has been sent!';
        }

        return 'Your donation to '.$output.' could not be sent.';
    }

    /**
     * Process Message.
     */
    public function process(string $type, User $user, string $message = '', int $targeted = 0): true|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $this->target = $user;

        if ($type === 'message') {
            $x = 0;
            $y = 1;
        } else {
            $x = 1;
            $y = 2;
        }

        if ($message === '') {
            $log = '';
        } else {
            $log = 'All '.$this->bot->name.' commands must be a private message or begin with /'.$this->bot->command.' or !'.$this->bot->command.'. Need help? Type /'.$this->bot->command.' help and you shall be helped.';
        }

        $command = @explode(' ', $message);

        $wildcard = null;
        $params = $command[$y] ?? null;

        if ($params) {
            $clone = $command;
            array_shift($clone);
            array_shift($clone);
            array_shift($clone);
            $wildcard = $clone;
        }

        if (\array_key_exists($x, $command)) {
            $log = match($command[$x]) {
                'banker'        => $this->getBanker(),
                'bans'          => $this->getBans(),
                'donations'     => $this->getDonations(),
                'donate'        => $this->putDonate((float) $params, $wildcard),
                'doubleupload'  => $this->getDoubleUpload(),
                'freeleech'     => $this->getFreeleech(),
                'help'          => $this->getHelp(),
                'king'          => $this->getKing(),
                'logins'        => $this->getLogins(),
                'peers'         => $this->getPeers(),
                'registrations' => $this->getRegistrations(),
                'uploads'       => $this->getUploads(),
                'warnings'      => $this->getWarnings(),
                'seeded'        => $this->getSeeded(),
                'leeched'       => $this->getLeeched(),
                'snatched'      => $this->getSnatched(),
                default         => '',
            };
        }

        $this->type = $type;
        $this->message = $message;
        $this->log = $log;

        return $this->pm();
    }

    /**
     * Output Message.
     */
    public function pm(): true|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $type = $this->type;
        $target = $this->target;
        $txt = $this->log;
        $message = $this->message;

        if ($type === 'message' || $type === 'private') {
            $receiverDirty = false;
            $receiverEchoes = cache()->get('user-echoes'.$target->id);

            if (!$receiverEchoes || !\is_array($receiverEchoes)) {
                $receiverEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', '=', $target->id)->get();
            }

            $receiverListening = false;

            foreach ($receiverEchoes as $receiverEcho) {
                if ($receiverEcho->bot_id === $this->bot->id) {
                    $receiverListening = true;

                    break;
                }
            }

            if (!$receiverListening) {
                $receiverPort = new UserEcho();
                $receiverPort->user_id = $target->id;
                $receiverPort->bot_id = $this->bot->id;
                $receiverPort->save();
                $receiverEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', '=', $target->id)->get();
                $receiverDirty = true;
            }

            if ($receiverDirty) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$target->id, $receiverEchoes, $expiresAt);
                event(new Chatter('echo', $target->id, UserEchoResource::collection($receiverEchoes)));
            }

            $receiverDirty = false;
            $receiverAudibles = cache()->get('user-audibles'.$target->id);

            if (!$receiverAudibles || !\is_array($receiverAudibles)) {
                $receiverAudibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', '=', $target->id)->get();
            }

            $receiverListening = false;

            foreach ($receiverAudibles as $receiverEcho) {
                if ($receiverEcho->bot_id === $this->bot->id) {
                    $receiverListening = true;

                    break;
                }
            }

            if (!$receiverListening) {
                $receiverPort = new UserAudible();
                $receiverPort->user_id = $target->id;
                $receiverPort->bot_id = $this->bot->id;
                $receiverPort->save();
                $receiverAudibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', '=', $target->id)->get();
                $receiverDirty = true;
            }

            if ($receiverDirty) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$target->id, $receiverAudibles, $expiresAt);
                event(new Chatter('audible', $target->id, UserAudibleResource::collection($receiverAudibles)));
            }

            if ($txt !== '') {
                $roomId = 0;
                $this->chatRepository->privateMessage($target->id, $roomId, $message, 1, $this->bot->id);
                $this->chatRepository->privateMessage(1, $roomId, $txt, $target->id, $this->bot->id);
            }

            return response('success');
        }

        if ($type === 'echo') {
            if ($txt !== '') {
                $roomId = 0;
                $this->chatRepository->botMessage($this->bot->id, $roomId, $txt, $target->id);
            }

            return response('success');
        }

        if ($type === 'public') {
            if ($txt !== '') {
                $this->chatRepository->message($target->id, $target->chatroom->id, $message, null, null);
                $this->chatRepository->message(1, $target->chatroom->id, $txt, null, $this->bot->id);
            }

            return response('success');
        }

        return true;
    }
}
