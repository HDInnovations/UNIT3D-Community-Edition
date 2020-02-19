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
use App\Models\BonTransactions;
use App\Models\Bot;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Notifications\NewBon;
use App\Repositories\ChatRepository;
use Carbon\Carbon;

class SystemBot
{
    private $bot;

    private $chat;

    private $target;

    private $type;

    private $message;

    private $targeted;

    private $log;

    /**
     * SystemBot Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $bot = Bot::where('slug', '=', 'systembot')->firstOrFail();
        $this->chat = $chat;
        $this->bot = $bot;
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
     * Get Help.
     */
    public function getHelp()
    {
        return $this->replaceVars($this->bot->help);
    }

    /**
     * Send Gift.
     *
     * @param string $receiver
     * @param int    $amount
     * @param string $note
     *
     * @return string
     */
    public function putGift($receiver = '', $amount = 0, $note = '')
    {
        $output = implode(' ', $note);
        $v = validator(['receiver' => $receiver, 'amount'=> $amount, 'note'=> $output], [
            'receiver'   => 'required|string|exists:users,username',
            'amount'     => sprintf('required|numeric|min:1|max:%s', $this->target->seedbonus),
            'note'       => 'required|string',
        ]);
        if ($v->passes()) {
            $recipient = User::where('username', 'LIKE', $receiver)->first();

            if (!$recipient || $recipient->id == $this->target->id) {
                return 'Your BON gift could not be sent.';
            }

            $value = $amount;
            $recipient->seedbonus += $value;
            $recipient->save();

            $this->target->seedbonus -= $value;
            $this->target->save();

            $transaction = new BonTransactions();
            $transaction->itemID = 0;
            $transaction->name = 'gift';
            $transaction->cost = $value;
            $transaction->sender = $this->target->id;
            $transaction->receiver = $recipient->id;
            $transaction->comment = $output;
            $transaction->torrent_id = null;
            $transaction->save();

            if ($this->target->id != $recipient->id && $recipient->acceptsNotification($this->target, $recipient, 'bon', 'show_bon_gift')) {
                $recipient->notify(new NewBon('gift', $this->target->username, $transaction));
            }

            $profile_url = hrefProfile($this->target);
            $recipient_url = hrefProfile($recipient);

            $this->chat->systemMessage(
                sprintf('[url=%s]%s[/url] has gifted %s BON to [url=%s]%s[/url]', $profile_url, $this->target->username, $value, $recipient_url, $recipient->username)
            );

            return 'Your gift to '.$recipient->username.' for '.$amount.' BON has been sent!';
        }

        return 'Your BON gift could not be sent.';
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
        $x = $type == 'message' ? 0 : 1;

        $y = $x + 1;
        $z = $y + 1;

        if ($message === '') {
            $log = '';
        } else {
            $log = 'All '.$this->bot->name.' commands must be a private message or begin with /'.$this->bot->command.' or !'.$this->bot->command.'. Need help? Type /'.$this->bot->command.' help and you shall be helped.';
        }
        $command = @explode(' ', $message);
        if (array_key_exists($x, $command)) {
            if ($command[$x] == 'gift' && array_key_exists($y, $command) && array_key_exists($z, $command) && array_key_exists($z + 1, $command)) {
                $clone = $command;
                array_shift($clone);
                array_shift($clone);
                array_shift($clone);
                array_shift($clone);
                $log = $this->putGift($command[$y], $command[$z], $clone);
            }
            if ($command[$x] == 'help') {
                $log = $this->getHelp();
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
