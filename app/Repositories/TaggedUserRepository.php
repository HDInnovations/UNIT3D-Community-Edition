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

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Notifications\NewCommentTag;
use App\Notifications\NewPostTag;

class TaggedUserRepository
{
    /**
     * Enables various debugging options:.
     *
     * 1. Allows you to tag yourself while testing and debugging
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $regex = '/@[a-zA-Z0-9-_]+/m';

    /**
     * @var User
     */
    private $user;

    /**
     * @var PrivateMessage
     */
    private $message;

    /**
     * TaggedUserRepository constructor.
     *
     * @param User           $user
     * @param PrivateMessage $message
     */
    public function __construct(User $user, PrivateMessage $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    public function getTags($content)
    {
        preg_match_all($this->regex, $content, $tagged);

        return $tagged[0];
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public function hasTags($content)
    {
        return $this->getTags($content) > 0;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public function contains($haystack, $needle)
    {
        return collect($this->getTags($haystack))->contains($needle);
    }

    public function messageTaggedCommentUsers(string $type, string $content, User $sender, $alias, Comment $comment)
    {
        foreach ($this->getTags($content) as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();
            $this->messageCommentUsers($type, $tagged_user, $sender, $alias, $comment);
        }

        return true;
    }

    public function messageCommentUsers($type, $users, $sender, $alias, Comment $comment)
    {
        // Array of User objects
        if (is_iterable($users)) {
            // we only want unique users from the collection
            $users = is_array($users) ? collect($users)->unique() : $users->unique();

            foreach ($users as $user) {
                if ($this->validate($user) && $user->acceptsNotification($sender, $user, 'mention', 'show_mention_'.$type.'_comment')) {
                    $user->notify(new NewCommentTag($type, $alias, $comment));
                }
            }

            return true;
        }

        // A single User object

        if ($this->validate($users) && $users->acceptsNotification($sender, $users, 'mention', 'show_mention_'.$type.'_comment')) {
            $users->notify(new NewCommentTag($type, $alias, $comment));
        }

        return true;
    }

    public function messageTaggedPostUsers(string $type, string $content, User $sender, $alias, Post $post)
    {
        foreach ($this->getTags($content) as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();
            $this->messagePostUsers($type, $tagged_user, $sender, $alias, $post);
        }

        return true;
    }

    public function messagePostUsers($type, $users, $sender, $alias, Post $post)
    {
        // Array of User objects
        if (is_iterable($users)) {
            // we only want unique users from the collection
            $users = is_array($users) ? collect($users)->unique() : $users->unique();

            foreach ($users as $user) {
                if ($this->validate($user) && $user->acceptsNotification($sender, $user, 'mention', 'show_mention_'.$type.'_post')) {
                    $user->notify(new NewPostTag($type, $alias, $post));
                }
            }

            return true;
        }

        // A single User object
        if ($this->validate($users) && $users->acceptsNotification($sender, $users, 'mention', 'show_mention_'.$type.'_post')) {
            $users->notify(new NewPostTag($type, $alias, $post));
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    protected function validate($user)
    {
        if (is_object($user)) {
            return true;
        }

        return false;
    }
}
