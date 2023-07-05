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

use App\Events\CreateMessage;
use App\Models\BonTransactions;
use App\Models\Bot;
use App\Models\Chatroom;
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
            $bots = Bot::query()
                ->where('active', '=', 1)
                ->where('id', '!=', $this->bot->id)
                ->orderByDesc('position')->get();

            foreach ($bots as $bot) {
                $botHelp .= '( ! | / | @)'.$bot->command.' help triggers help file for '.$bot->name."\n";
            }

            $output = str_replace('{bots}', $botHelp, $output);
        }

        return $output;
    }

    /**
     * Send gift.
     * @param string[] $arguments
     */
    public function gift(array $arguments): Message
    {
        Validator::make($arguments, [
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

        ['username' => $username, 'amount' => $amount, 'message' => $message] = $arguments;

        $recipient = User::where('username', '=', $username)->sole();

        DB::transaction(function () use ($recipient, $amount): void {
            $recipient->increment('seedbonus', (float) $amount);
            auth()->user()->decrement('seedbonus', (float) $amount);
        });

        $bonTransactions = BonTransactions::create([
            'itemID'     => 0,
            'name'       => 'gift',
            'cost'       => $amount,
            'sender'     => auth()->id(),
            'receiver'   => $recipient->id,
            'comment'    => $message,
            'torrent_id' => null,
        ]);

        if ($recipient->acceptsNotification(auth()->user(), $recipient, 'bon', 'show_bon_gift')) {
            $recipient->notify(new NewBon('gift', auth()->user()->username, $bonTransactions));
        }

        return Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => Chatroom::query()
                ->where('name', '=', config('chat.system_chatroom'))
                ->orWhere('id', '=', config('chat.system_chatroom'))
                ->sole()
                ->id,
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
     *
     * @param array<string, ?int> $echo
     */
    public function getHelp(array $echo): Message
    {
        return Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => $this->echo['room_id'] ?? 0,
            'receiver_id' => $this->echo['target_id'] ?? null,
            'bot_id'      => $this->echo['bot_id'] ?? null,
            'message'     => $this->replaceVars($this->bot->help),
        ]);
    }

    /**
     * Process Message.
     *
     * @param array<string, ?int> $echo
     * @param array<string>       $arguments
     */
    public function handle(?string $command, array $arguments = [], array $echo = []): void
    {
        $message = match ($command) {
            'gift'  => $this->gift($arguments),
            default => $this->getHelp($echo),
        };

        CreateMessage::dispatch($message, $echo);
    }
}
