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

namespace App\Listeners;

use App\Models\User;
use App\Repositories\ChatRepository;
use Gstt\Achievements\Event\Unlocked;
use Session;

class AchievementUnlocked
{
    private $chat;

    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Handle the event.
     *
     * @param $event
     *
     * @return void
     */
    public function handle(Unlocked $event)
    {
        // There's an AchievementProgress instance located on $event->progress
        $user = User::where('id', '=', $event->progress->achiever_id)->first();
        Session::flash('achievement', $event->progress->details->name);

        if ($user->private_profile == 0) {
            $profile_url = hrefProfile($user);

            $this->chat->systemMessage(
                sprintf('User [url=%s]%s[/url] has unlocked the %s achievement :medal:', $profile_url, $user->username, $event->progress->details->name)
            );
        }
    }
}
