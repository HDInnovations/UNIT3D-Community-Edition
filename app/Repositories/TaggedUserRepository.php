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
     * @var User
     */
    private $user;

    /**
     * @var PrivateMessage
     */
    private $message;

    public function __construct(User $user, PrivateMessage $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @param string $content The content string to search for usernames in
     * @param string $subject The subject of the message sent to the user
     * @param string $message The message body of the message sent to the user
     */
    public function messageTaggedUsers(string $content, string $subject, string $message)
    {
        preg_match_all('/@\w[a-zA-Z0-9-_]+/m', $content, $tagged);

        foreach ($tagged[0] as $username) {
            $tagged_user = $this->user->where('username', str_replace('@', '', $username))->first();

            if ($tagged_user) {
                if ($this->debug || $tagged_user->id !== auth()->user()->id) {
                    $this->message->create([
                        'sender_id' => 1,
                        'reciever_id' => $tagged_user->id,
                        'subject' => $subject,
                        'message' => $message
                    ]);
                }
            }
        }
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
}