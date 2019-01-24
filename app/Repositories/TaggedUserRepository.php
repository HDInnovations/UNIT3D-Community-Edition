<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Repositories;

use App\User;
use App\Post;
use App\Comment;
use App\PrivateMessage;
use App\Notifications\NewPostTag;
use App\Notifications\NewCommentTag;

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

    /**
     * @param string $type
     * @param string $content
     * @param string $sender
     * @param $comment
     */
    public function messageTaggedCommentUsers(string $type, string $content, string $sender, Comment $comment)
    {
        foreach ($this->getTags($content) as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();
            $this->messageCommentUsers($type, $tagged_user, $sender, $comment);
        }

        return true;
    }

    /**
     * @param string $type
     * @param $users
     * @param $sender
     * @param $comment
     *
     * @return bool
     */
    public function messageCommentUsers($type, $users, $sender, Comment $comment)
    {
        // Array of User objects
        if (is_iterable($users)) {
            // we only want unique users from the collection
            $users = is_array($users) ? collect($users)->unique() : $users->unique();

            foreach ($users as $user) {
                if ($this->validate($user)) {
                    $user->notify(new NewCommentTag($type,$sender,$comment));
                }
            }
            return true;
        }

        // A single User object

        if ($this->validate($users)) {
            $users->notify(new NewCommentTag($type,$sender,$comment));
        }
        return true;
    }

    /**
     * @param string $type
     * @param string $content
     * @param string $sender
     * @param $post
     */
    public function messageTaggedPostUsers(string $type, string $content, string $sender, Post $post)
    {
        foreach ($this->getTags($content) as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();
            $this->messagePostUsers($type, $tagged_user, $sender, $post);
        }

        return true;
    }

    /**
     * @param string $type
     * @param $users
     * @param $sender
     * @param $post
     *
     * @return bool
     */
    public function messagePostUsers($type, $users, $sender, Post $post)
    {
        // Array of User objects
        if (is_iterable($users)) {
            // we only want unique users from the collection
            $users = is_array($users) ? collect($users)->unique() : $users->unique();

            foreach ($users as $user) {
                if ($this->validate($user)) {
                    $user->notify(new NewPostTag($type,$sender,$post));
                }
            }
            return true;
        }

        // A single User object

        if ($this->validate($users)) {
            $users->notify(new NewPostTag($type,$sender,$post));
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
