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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentTag extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewCommentTag Constructor.
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
        $title = $this->comment->anon ? 'You Have Been Tagged' : $username.' Has Tagged You';

        return match (true) {
            $this->model instanceof Torrent => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Torrent '.$this->model->name,
                'url'   => '/torrents/'.$this->model->id,
            ],
            $this->model instanceof TorrentRequest => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Torrent Request '.$this->model->name,
                'url'   => '/requests/'.$this->model->id,
            ],
            $this->model instanceof Ticket => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Ticket '.$this->model->subject,
                'url'   => '/tickets/'.$this->model->id,
            ],
            $this->model instanceof Playlist => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Playlist '.$this->model->name,
                'url'   => '/playlists/'.$this->model->id,
            ],
            $this->model instanceof Collection => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Collection '.$this->model->name,
                'url'   => '/mediahub/collections/'.$this->model->id,
            ],
            $this->model instanceof Article => [
                'title' => $title,
                'body'  => $username.' has tagged you in an comment on Article '.$this->model->title,
                'url'   => '/articles/'.$this->model->id,
            ],
        };
    }
}
