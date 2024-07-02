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

namespace App\Bots;

use App\Events\MessageCreated;
use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\Gift;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewBon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Closure;

class SystemBot
{
    private Bot $bot;

    public function __construct()
    {
        $this->bot = Bot::where('is_systembot', '=', true)->sole();
    }

    public function replaceVars(string $output): string
    {
        $output = str_replace(['{me}', '{command}'], [$this->bot->name, $this->bot->command], $output);

        if (str_contains($output, '{bots}')) {
            $botHelp = '';
            $bots = Bot::where('active', '=', 1)->where('id', '!=', $this->bot->id)->oldest('position')->get();

            foreach ($bots as $bot) {
                $botHelp .= '( ! | / | @)'.$bot->command.' help triggers help file for '.$bot->name."\n";
            }

            $output = str_replace('{bots}', $botHelp, $output);
        }

        return $output;
    }

    public function gift(?string $username, ?string $amount, ?string $message): Message
    {
        Validator::make([
            'username' => $username,
            'amount'   => $amount,
            'message'  => $message,
        ], [
            'username' => [
                'required',
                Rule::exists('users', 'username')->whereNot('id', auth()->id()),
            ],
            'amount' => [
                'required',
                'integer',
                'gte:1',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value > auth()->user()->seedbonus) {
                        $fail('You do not have enough BON to send this gift.');
                    }
                }
            ],
            'message' => [
                'required',
            ],
        ])->validate();

        $recipient = User::where('username', '=', $username)->sole();

        DB::transaction(function () use ($recipient, $amount): void {
            $recipient->increment('seedbonus', (float) $amount);
            auth()->user()->decrement('seedbonus', (float) $amount);
        });

        $gift = Gift::create([
            'sender_id'    => auth()->id(),
            'recipient_id' => $recipient->id,
            'bon'          => $amount,
            'message'      => $message,
        ]);

        if ($recipient->acceptsNotification(auth()->user(), $recipient, 'bon', 'show_bon_gift')) {
            $recipient->notify(new NewBon($gift));
        }

        return Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => Chatroom::query()
                ->where(\is_int(config('chat.system_chatroom')) ? 'id' : 'name', '=', config('chat.system_chatroom'))
                ->soleValue('id'),
            'message' => sprintf(
                '[url=%s]%s[/url] has gifted %s BON to [url=%s]%s[/url]',
                href_profile(auth()->user()),
                auth()->user()->username,
                $amount,
                href_profile($recipient),
                $recipient->username
            ),
            'receiver_id' => null,
            'bot_id'      => $this->bot->id,
        ]);
    }

    /**
     * Get Help.
     */
    public function getHelp(?int $roomId = null, ?int $receiverId = null): Message
    {
        return Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => $roomId,
            'receiver_id' => $receiverId ?? null,
            'message'     => $this->replaceVars($this->bot->help),
        ]);
    }

    /**
     * Process Message.
     */
    public function handle(?string $message, ?int $roomId = null, ?int $receiverId = null): void
    {
        [, $command, $arguments] = preg_split('/ +/', $message, 3) + [null, null, null];

        switch ($command) {
            case 'gift':
                [$username, $amount, $message] = preg_split('/ +/', $arguments, 3) + [null, null, null];
                $message = $this->gift($username, $amount, $message);

                break;
            default:
                $message = $this->getHelp($roomId, $receiverId);
        }

        MessageCreated::dispatch($message, User::SYSTEM_USER_ID, $roomId, $receiverId);
    }
}
