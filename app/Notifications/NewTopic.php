<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie, singularity43
 */

namespace App\Notifications;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewTopic extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var \App\Models\User
     */
    public User $poster;

    /**
     * @var \App\Models\Topic
     */
    public Topic $topic;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  User  $poster
     * @param  Topic  $topic
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     */
    public function __construct(string $type, User $poster, Topic $topic, Repository $configRepository)
    {
        $this->type = $type;
        $this->topic = $topic;
        $this->poster = $poster;
        $this->configRepository = $configRepository;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function toArray($notifiable): array
    {
        $appurl = $this->configRepository->get('app.url');

        return [
            'title' => $this->poster->username.' Has Posted In A Subscribed Forum',
            'body' => $this->poster->username.' has started a new topic in '.$this->topic->forum->name,
            'url' => sprintf('/forums/topics/%s', $this->topic->id),
        ];
    }
}
