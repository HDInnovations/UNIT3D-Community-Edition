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

use App\Post;

class NewTopicPost extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
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
        return [
            'title' => "Subscribed Topic Has A New Post",
            'body' => $this->post->user->username . " has left a new post on " . $this->post->topic->name,
            'url' => "{$appurl}/forums/topic/{$this->post->topic->slug}.{$this->post->topic->id}?page={$this->post->getPageNumber()}#post-{$this->post->id}"
        ];
    }
}
