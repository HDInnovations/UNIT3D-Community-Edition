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
use App\Models\Bot;
use App\Models\BotTransaction;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Repositories\ChatRepository;
use Illuminate\Support\Carbon;
use Exception;

class CasinoBot
{
    private Bot $bot;

    private ?User $target = null;

    private string $type;

    private ?string $message = null;

    private ?string $log = null;

    private readonly Carbon $expiresAt;

    /**
     * NerdBot Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
        $this->bot = Bot::findOrFail(3);
        $this->expiresAt = Carbon::now()->addMinutes(60);
    }

    /**
     * Replace Vars.
     */
    public function replaceVars(?string $output): string
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
     * Get Bot Donations.
     *
     *
     * @throws Exception
     */
    public function getDonations(string $duration = 'default'): string
    {
        $donations = cache()->get('casinobot-donations');

        if (!$donations) {
            $donations = BotTransaction::with('user', 'bot')->where('bot_id', '=', $this->bot->id)->where('to_bot', '=', 1)->latest()->limit(10)->get();
            cache()->put('casinobot-donations', $donations, $this->expiresAt);
        }

        $donationDump = '';
        $i = 1;

        foreach ($donations as $donation) {
            $donationDump .= '#'.$i.'. '.$donation->user->username.' sent '.$donation->cost.' '.$donation->forHumans().' with note: '.$donation->comment.".\n";
            $i++;
        }

        return "The Most Recent Donations To Me Are As Follows:\n\n".trim($donationDump);
    }

    /**
     * Get Help.
     */
    public function getHelp(): string
    {
        return $this->replaceVars($this->bot->help);
    }

    /**
     * Process Message.
     *
     * @throws Exception
     */
    public function process(string $type, User $user, string $message = '', int $targeted = 0): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|bool
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
            if ($command[$x] === 'donations') {
                $log = $this->getDonations($params);
            }

            if ($command[$x] === 'donate') {
                $log = $this->putDonate((float) $params, $wildcard);
            }
        }

        $this->type = $type;
        $this->message = $message;
        $this->log = $log;

        return $this->pm();
    }

    /**
     * Output Message.
     */
    public function pm(): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|bool
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
