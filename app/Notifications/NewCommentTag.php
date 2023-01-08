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

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentTag extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewCommentTag Constructor.
     */
    public function __construct(public string $type, public Comment $comment)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        if ($this->type == 'torrent') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' Has Tagged You',
                    'body'  => $this->comment->user->username.' has tagged you in an comment on Torrent '.$this->comment->commentable->name,
                    'url'   => '/torrents/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'You Have Been Tagged',
                'body'  => 'Anonymous has tagged you in an comment on Torrent '.$this->comment->commentable->name,
                'url'   => '/torrents/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'torrentrequest') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' Has Tagged You',
                    'body' => $this->comment->user->username.' has tagged you in an comment on Torrent Request '.$this->comment->commentable->name,
                    'url' => '/requests/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'You Have Been Tagged',
                'body' => 'Anonymous has tagged you in an comment on Torrent Request '.$this->comment->commentable->name,
                'url' => '/requests/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'ticket') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' Has Tagged You',
                    'body' => $this->comment->user->username.' has tagged you in an comment on Ticket '.$this->comment->commentable->subject,
                    'url' => '/tickets/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'You Have Been Tagged',
                'body' => 'Anonymous has tagged you in an comment on Ticket '.$this->comment->commentable->subject,
                'url' => '/tickets/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'playlist') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' Has Tagged You',
                    'body' => $this->comment->user->username.' has tagged you in an comment on Playlist '.$this->comment->commentable->name,
                    'url' => '/playlists/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'You Have Been Tagged',
                'body' => 'Anonymous has tagged you in an comment on Playlist '.$this->comment->commentable->name,
                'url' => '/playlists/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'collection') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' Has Tagged You',
                    'body' => $this->comment->user->username.' has tagged you in an comment on Collection '.$this->comment->commentable->name,
                    'url' => '/mediahub/collections/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'You Have Been Tagged',
                'body' => 'Anonymous has tagged you in an comment on Collection '.$this->comment->commentable->name,
                'url' => '/mediahub/collections/'.$this->comment->commentable->id,
            ];
        }

        if ($this->comment->anon == 0) {
            return [
                'title' => $this->comment->user->username.' Has Tagged You',
                'body' => $this->comment->user->username.' has tagged you in an comment on Article '.$this->comment->commentable->title,
                'url' => '/articles/'.$this->comment->commentable->id,
            ];
        }

        return [
            'title' => 'You Have Been Tagged',
            'body' => 'Anonymous has tagged you in an comment on Article '.$this->comment->commentable->title,
            'url' => '/articles/'.$this->comment->commentable->id,
        ];
    }
}
