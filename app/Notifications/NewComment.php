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
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class NewComment extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var \App\Models\Comment
     */
    public Comment $comment;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  Comment  $comment
     */
    public function __construct(string $type, Comment $comment, Repository $configRepository)
    {
        $this->type = $type;
        $this->comment = $comment;
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
        if ($this->type == 'torrent') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => 'New Torrent Comment Received',
                    'body' => $this->comment->user->username.' has left you a comment on Torrent '.$this->comment->torrent->name,
                    'url' => '/torrents/'.$this->comment->torrent->id,
                ];
            } else {
                return [
                    'title' => 'New Torrent Comment Received',
                    'body' => 'Anonymous has left you a comment on Torrent '.$this->comment->torrent->name,
                    'url' => '/torrents/'.$this->comment->torrent->id,
                ];
            }
        }
        if ($this->comment->anon == 0) {
            return [
                'title' => 'New Request Comment Received',
                'body'  => $this->comment->user->username.' has left you a comment on Torrent Request '.$this->comment->request->name,
                'url'   => '/requests/'.$this->comment->request->id,
            ];
        } else {
            return [
                'title' => 'New Request Comment Received',
                'body'  => 'Anonymous has left you a comment on Torrent Request '.$this->comment->request->name,
                'url'   => '/requests/'.$this->comment->request->id,
            ];
        }
    }
}
