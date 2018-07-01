<?php

namespace App\Repositories;

use App\PrivateMessage;
use App\User;

class TaggedUserRepository
{
    /**
     * Enables various debugging options:
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
     * @param User $user
     * @param PrivateMessage $message
     */
    public function __construct(User $user, PrivateMessage $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @param $content
     * @return mixed
     */
    public function getTags($content)
    {
        preg_match_all($this->regex, $content, $tagged);
        return $tagged[0];
    }

    /**
     * @param $content
     * @return bool
     */
    public function hasTags($content)
    {
        return $this->getTags($content) > 0;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function contains($haystack, $needle)
    {
        return collect($this->getTags($haystack))->contains($needle);
    }

    /**
     * @param string $content
     * @param string $subject
     * @param string $message
     */
    public function messageTaggedUsers(string $content, string $subject, string $message)
    {
        foreach ($this->getTags($content) as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();
            $this->messageUsers($tagged_user, $subject, $message);

        }

        return true;
    }

    /**
     * @param $users
     * @param $subject
     * @param $message
     * @return bool
     */
    public function messageUsers($users, $subject, $message)
    {
        // Array of User objects
        if (is_iterable($users)) {

            // we only want unique users from the collection
            $users = is_array($users) ? collect($users)->unique() : $users->unique();

            foreach ($users as $user) {
                if ($this->validate($user)) {
                    $pm = new PrivateMessage();
                    $pm->sender_id = 1;
                    $pm->receiver_id = $user->id;
                    $pm->subject = $subject;
                    $pm->message = $message;
                    $pm->save();
                }
            }

            return true;
        }

        // A single User object
        if ($this->validate($users)) {
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $users->id;
            $pm->subject = $subject;
            $pm->message = $message;
            $pm->save();
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
        if ($this->debug || $user->id !== auth()->user()->id) {
            return true;
        }

        return false;
    }
}