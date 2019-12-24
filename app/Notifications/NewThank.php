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

use App\Models\Thank;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Notifications\Notification;

final class NewThank extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var \App\Models\Thank
     */
    public Thank $thank;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  Thank  $thank
     */
    public function __construct(string $type, Thank $thank, Repository $configRepository)
    {
        $this->type = $type;
        $this->thank = $thank;
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
            'title' => $this->thank->user->username.' Has Thanked You For An Uploaded Torrent',
            'body' => $this->thank->user->username.' has left you a thanks on Uploaded Torrent '.$this->thank->torrent->name,
            'url' => '/torrents/'.$this->thank->torrent->id,
        ];
    }
}
