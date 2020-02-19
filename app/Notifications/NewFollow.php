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

use App\Models\Follow;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollow extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    public $sender;

    public $follow;

    public $target;

    /**
     * Create a new notification instance.
     *
     * @param string $type
     * @param User   $sender
     * @param User   $target
     * @param Follow $follow
     */
    public function __construct(string $type, User $sender, User $target, Follow $follow)
    {
        $this->type = $type;
        $this->follow = $follow;
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
            'title' => $this->sender->username.' Has Followed You!',
            'body'  => $this->sender->username.' has started to follow you so they will get notifications about your activities.',
            'url'   => sprintf('/users/%s', $this->sender->username),
        ];
    }
}
