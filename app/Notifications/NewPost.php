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
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewPost extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Models\Post
     */
    public Post $post;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var \App\Models\User
     */
    public User $poster;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     *
     * @param  User  $poster
     * @param  Post  $post
     */
    public function __construct(string $type, User $poster, Post $post, Repository $configRepository)
    {
        $this->poster = $poster;
        $this->post = $post;
        $this->type = $type;
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

        if ($this->type == 'subscription') {
            return [
                'title' => $this->poster->username.' Has Posted In A Subscribed Topic',
                'body' => $this->poster->username.' has left a new post in Subscribed Topic '.$this->post->topic->name,
                'url' => sprintf('/forums/topics/%s?page=%s#post-%s', $this->post->topic->id, $this->post->getPageNumber(), $this->post->id),
            ];
        } else {
            return [
                'title' => $this->poster->username.' Has Posted In A Topic You Started',
                'body' => $this->poster->username.' has left a new post in Your Topic '.$this->post->topic->name,
                'url' => sprintf('/forums/topics/%s?page=%s#post-%s', $this->post->topic->id, $this->post->getPageNumber(), $this->post->id),
            ];
        }
    }
}
