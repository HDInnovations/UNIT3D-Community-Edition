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

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPost extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewPost Constructor.
     */
    public function __construct(public string $type, public User $user, public Post $post)
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
     * Determine if the notification should be sent.
     */
    public function shouldSend(User $notifiable): bool
    {
        $targetGroup = '';

        switch ($this->type) {
            case 'subscription':
                $targetGroup = 'json_subscription_groups';

                break;
            case 'staff':
                $targetGroup = '';

                break;
            case 'topic':
                $targetGroup = 'json_forum_groups';

                break;
            default:
                $targetGroup = 'json_forum_groups';

                break;
        }

        // Do not notify the poster theirself
        if ($this->post->user_id === $notifiable->id) {
            return false;
        }

        if ($notifiable->notification?->block_notifications == 1) {
            return false;
        }

        if (!$notifiable->notification?->show_forum_topic) {
            return false;
        }

        if (\is_array($notifiable->notification->$targetGroup)) {
            // If the sender's group ID is found in the "Block all notifications from the selected groups" array,
            // the expression will return false.
            return !\in_array($this->user->group_id, $notifiable->notification->$targetGroup, true);
        }

        return true;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type == 'subscription') {
            return [
                'title' => $this->user->username.' Has Posted In A Subscribed Topic',
                'body'  => $this->user->username.' has left a new post in Subscribed Topic '.$this->post->topic->name,
                'url'   => \sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
            ];
        }

        if ($this->type == 'staff') {
            return [
                'title' => $this->user->username.' Has Posted In A Staff Forum Topic',
                'body'  => $this->user->username.' has left a new post in Staff Topic '.$this->post->topic->name,
                'url'   => \sprintf('%s/posts/%s', route('topics.show', ['id' => $this->post->topic->id]), $this->post->id),
            ];
        }

        return [
            'title' => $this->user->username.' Has Posted In A Topic You Started',
            'body'  => $this->user->username.' has left a new post in Your Topic '.$this->post->topic->name,
            'url'   => \sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
        ];
    }
}
