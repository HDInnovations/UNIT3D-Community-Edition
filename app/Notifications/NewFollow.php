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

use App\Models\Follow;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewFollow extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var \App\Models\User
     */
    public User $sender;

    /**
     * @var \App\Models\Follow
     */
    public Follow $follow;

    /**
     * @var \App\Models\User
     */
    public User $target;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  User  $sender
     * @param  User  $target
     * @param  Follow  $follow
     */
    public function __construct(string $type, User $sender, User $target, Follow $follow, Repository $configRepository)
    {
        $this->type = $type;
        $this->follow = $follow;
        $this->sender = $sender;
        $this->target = $target;
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
            'title' => $this->sender->username.' Has Followed You!',
            'body'  => $this->sender->username.' has started to follow you so they will get notifications about your activities.',
            'url'   => sprintf('/users/%s', $this->sender->username),
        ];
    }
}
