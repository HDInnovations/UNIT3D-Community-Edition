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

use App\Models\TorrentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestFill extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    public $sender;

    public $tr;

    /**
     * Create a new notification instance.
     *
     * @param string         $type
     * @param string         $sender
     * @param TorrentRequest $tr
     */
    public function __construct(string $type, string $sender, TorrentRequest $tr)
    {
        $this->type = $type;
        $this->sender = $sender;
        $this->tr = $tr;
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
            'title' => $this->sender.' Has Filled One Of Your Torrent Requests',
            'body'  => $this->sender.' has filled one of your Requested Torrents '.$this->tr->name,
            'url'   => sprintf('/requests/%s', $this->tr->id),
        ];
    }
}
