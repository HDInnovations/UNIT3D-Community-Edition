<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie, singularity43
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUnfollow extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;
    public $sender_username;
    public $sender_id;

    /**
     * Create a new notification instance.
     *
     * @param Follow $follow
     *
     * @return void
     */
    public function __construct(string $type, string $sender_username, int $sender_id)
    {
        $this->type = $type;
        $this->sender_username = $sender_username;
        $this->sender_id = $sender_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        $appurl = config('app.url');

        return [
            'title' => $this->sender_username.' Has Unfollowed You!',
            'body'  => $this->sender_username.' has stopped following you so they will no longer get notifications about your activities.',
            'url'   => "/".$this->sender_username.".".$this->sender_id,
        ];

    }
}
