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

use Illuminate\Contracts\Config\Repository;
use App\Models\Torrent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewUploadTip extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $tipper;

    /**
     * @var \App\Models\Torrent
     */
    public Torrent $torrent;

    public $amount;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  string  $tipper
     * @param $amount
     * @param  Torrent  $torrent
     */
    public function __construct(string $type, string $tipper, $amount, Torrent $torrent, Repository $configRepository)
    {
        $this->type = $type;
        $this->tipper = $tipper;
        $this->torrent = $torrent;
        $this->amount = $amount;
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
            'title' => $this->tipper.' Has Tipped You '.$this->amount.' BON For An Uploaded Torrent',
            'body'  => $this->tipper.' has tipped one of your Uploaded Torrents '.$this->torrent->name,
            'url'   => sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
