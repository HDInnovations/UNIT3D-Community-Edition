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

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewUnfollow extends Notification implements ShouldQueue
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
     * @var \App\Models\User
     */
    public User $target;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  User  $sender
     * @param  User  $target
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     */
    public function __construct(string $type, User $sender, User $target, Repository $configRepository)
    {
        $this->type = $type;
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
            'title' => $this->sender->username.' Has Unfollowed You!',
            'body'  => $this->sender->username.' has stopped following you so they will no longer get notifications about your activities.',
            'url'   => '/users/'.$this->sender->username,
        ];
    }
}
