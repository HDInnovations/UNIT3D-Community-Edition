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

use App\Models\TorrentRequestBounty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestBounty extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewRequestBounty Constructor.
     */
    public function __construct(public TorrentRequestBounty $bounty)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->bounty->load('user');

        return [
            'title' => ($this->bounty->anon ? 'Anonymous' : $this->bounty->user->username).' Has Added A Bounty Of '.$this->bounty->seedbonus.' To A Requested Torrent',
            'body'  => ($this->bounty->anon ? 'Anonymous' : $this->bounty->user->username).' has added a bounty to one of your Requested Torrents '.$this->bounty->request->name,
            'url'   => sprintf('/requests/%s', $this->bounty->requests_id),
        ];
    }
}
