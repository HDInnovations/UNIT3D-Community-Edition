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

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUnfollow extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    public $sender;

    public $target;

    /**
     * Create a new notification instance.
     *
     * @param string $type
     * @param User   $sender
     * @param User   $target
     */
    public function __construct(string $type, User $sender, User $target)
    {
        $this->type = $type;
        $this->sender = $sender;
        $this->target = $target;
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
            'title' => $this->sender->username.' Has Unfollowed You!',
            'body'  => $this->sender->username.' has stopped following you so they will no longer get notifications about your activities.',
            'url'   => '/users/'.$this->sender->username,
        ];
    }
}
