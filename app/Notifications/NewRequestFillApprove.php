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

class NewRequestFillApprove extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewRequestFillApprove Constructor.
     */
    public function __construct(public TorrentRequest $torrentRequest)
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
        if ($this->torrentRequest->anon == 0) {
            $this->torrentRequest->load('approver');

            return [
                'title' => $this->torrentRequest->approver->username.' Has Approved Your Fill Of A Requested Torrent',
                'body'  => $this->torrentRequest->approver->username.' has approved your fill of Requested Torrent '.$this->torrentRequest->name,
                'url'   => sprintf('/requests/%s', $this->torrentRequest->id),
            ];
        }

        return [
            'title' => 'An anonymous user has Approved Your Fill Of A Requested Torrent',
            'body'  => 'An anonymous user has approved your fill of Requested Torrent '.$this->torrentRequest->name,
            'url'   => sprintf('/requests/%s', $this->torrentRequest->id),
        ];
    }
}
