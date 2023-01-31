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
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    /**
     * NewComment Constructor.
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
                    'title' => 'New Torrent Comment Received',
                    'body'  => $this->comment->user->username.' has left you a comment on Torrent '.$this->comment->commentable->name,
                    'url'   => '/torrents/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'New Torrent Comment Received',
                'body'  => 'Anonymous has left you a comment on Torrent '.$this->comment->commentable->name,
                'url'   => '/torrents/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'torrentrequest') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => 'New Request Comment Received',
                    'body' => $this->comment->user->username.' has left you a comment on Torrent Request '.$this->comment->commentable->name,
                    'url' => '/requests/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'New Request Comment Received',
                'body' => 'Anonymous has left you a comment on Torrent Request '.$this->comment->commentable->name,
                'url' => '/requests/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'ticket') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => 'New Ticket Comment Received',
                    'body' => $this->comment->user->username.' has left you a comment on Ticket '.$this->comment->commentable->subject,
                    'url' => '/tickets/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'New Ticket Comment Received',
                'body' => 'Anonymous has left you a comment on Ticket '.$this->comment->commentable->subject,
                'url' => '/tickets/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'playlist') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => 'New Playlist Comment Received',
                    'body' => $this->comment->user->username.' has left you a comment on Playlist '.$this->comment->commentable->name,
                    'url' => '/playlists/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'New Playlist Comment Received',
                'body' => 'Anonymous has left you a comment on Playlist '.$this->comment->commentable->name,
                'url' => '/playlists/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type == 'collection') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => 'New Collection Comment Received',
                    'body' => $this->comment->user->username.' has left you a comment on Collection '.$this->comment->commentable->name,
                    'url' => '/mediahub/collections/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => 'New Collection Comment Received',
                'body' => 'Anonymous has left you a comment on Collection '.$this->comment->commentable->name,
                'url' => '/mediahub/collections/'.$this->comment->commentable->id,
            ];
        }

        if ($this->comment->anon == 0) {
            return [
                'title' => 'New Article Comment Received',
                'body' => $this->comment->user->username.' has left you a comment on Article '.$this->comment->commentable->title,
                'url' => '/articles/'.$this->comment->commentable->id,
            ];
        }

        return [
            'title' => 'New Article Comment Received',
            'body' => 'Anonymous has left you a comment on Article '.$this->comment->commentable->title,
            'url' => '/articles/'.$this->comment->commentable->id,
        ];
    }
}
