<?php

declare(strict_types=1);

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

use App\Models\Article;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Playlist;
use App\Models\Ticket;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    /**
     * NewComment Constructor.
     */
    public function __construct(public Torrent|TorrentRequest|Ticket|Playlist|Collection|Article $model, public Comment $comment)
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
        $username = $this->comment->anon ? 'Anonymous' : $this->comment->user->username;

        return match (true) {
            $this->model instanceof Torrent => [
                'title' => 'New Torrent Comment Received',
                'body'  => $username.' has left you a comment on Torrent '.$this->model->name,
                'url'   => '/torrents/'.$this->model->id,
            ],
            $this->model instanceof TorrentRequest => [
                'title' => 'New Request Comment Received',
                'body'  => $username.' has left you a comment on Torrent Request '.$this->model->name,
                'url'   => '/requests/'.$this->model->id,
            ],
            $this->model instanceof Ticket => [
                'title' => 'New Ticket Comment Received',
                'body'  => $username.' has left you a comment on Ticket '.$this->model->subject,
                'url'   => '/tickets/'.$this->model->id,
            ],
            $this->model instanceof Playlist => [
                'title' => 'New Playlist Comment Received',
                'body'  => $username.' has left you a comment on Playlist '.$this->model->name,
                'url'   => '/playlists/'.$this->model->id,
            ],
            $this->model instanceof Collection => [
                'title' => 'New Collection Comment Received',
                'body'  => $username.' has left you a comment on Collection '.$this->model->name,
                'url'   => '/mediahub/collections/'.$this->model->id,
            ],
            $this->model instanceof Article => [
                'title' => 'New Article Comment Received',
                'body'  => $username.' has left you a comment on Article '.$this->model->title,
                'url'   => '/articles/'.$this->model->id,
            ],
        };
    }
}
