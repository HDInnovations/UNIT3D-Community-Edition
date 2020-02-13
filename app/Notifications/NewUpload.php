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

use App\Models\Torrent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUpload extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    public $torrent;

    /**
     * Create a new notification instance.
     *
     * @param string  $type
     * @param Torrent $torrent
     *
     * @return void
     */
    public function __construct(string $type, Torrent $torrent)
    {
        $this->type = $type;
        $this->torrent = $torrent;
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
            'title' => $this->torrent->user->username.' Has Uploaded A New Torrent',
            'body'  => "{$this->torrent->user->username}, whom you are following has uploaded Torrent {$this->torrent->name}",
            'url'   => "/torrents/{$this->torrent->id}",
        ];
    }
}
