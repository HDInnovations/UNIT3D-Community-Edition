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

use App\Models\Topic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewTopic extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    public $poster;

    public $topic;

    /**
     * Create a new notification instance.
     *
     * @param string $type
     * @param User   $poster
     * @param Topic  $topic
     */
    public function __construct(string $type, User $poster, Topic $topic)
    {
        $this->type = $type;
        $this->topic = $topic;
        $this->poster = $poster;
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
            'title' => $this->poster->username.' Has Posted In A Subscribed Forum',
            'body'  => $this->poster->username.' has started a new topic in '.$this->topic->forum->name,
            'url'   => sprintf('/forums/topics/%s', $this->topic->id),
        ];
    }
}
