<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Listeners;

use Gstt\Achievements\Event\Unlocked;
use App\Shoutbox;
use App\User;

class AchievementUnlocked
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle(Unlocked $event)
    {
        // There's an AchievementProgress instance located on $event->progress
        $user = User::where('id', '=', $event->progress->achiever_id)->first();
        session()->flash('achievement', $event->progress->details->name);

        if ($user->private_profile == 0) {
            $appurl = config('app.url');
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has unlocked the " . $event->progress->details->name . " achievement :medal:"]);
            cache()->forget('shoutbox_messages');
        }
    }
}
