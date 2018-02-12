<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Request;

use App\Comment;

class NewTorrentComment extends Notification
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $appurl = config('app.url');
        if ($this->comment->anon == 0) {
            return [
                'title' => "New Torrent Comment Recieved",
                'body' => $this->comment->user->username . " has left you a comment on " . $this->comment->torrent->name,
                'url' => $appurl . '/torrents/' . $this->comment->torrent->slug . '.' . $this->comment->torrent->id
            ];
        } else {
            return [
                'title' => "New Torrent Comment Recieved",
                'body' => "A anonymous member has left you a comment on " . $this->comment->torrent->name,
                'url' => $appurl . '/torrents/' . $this->comment->torrent->slug . '.' . $this->comment->torrent->id
            ];
        }
    }
}
