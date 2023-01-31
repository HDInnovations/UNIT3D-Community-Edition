<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMaxWarningsReached extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $profileUrl = \href_profile($this->user);

        return (new MailMessage())
            ->greeting('Max Hit and Run Warnings Reached!')
            ->line('You have hit the limit on active Hit and Run Warnings! Your download privilliges have been revoked!')
            ->action('View Unsatfied Torrents to seed off your warnings or wait until they expire!', $profileUrl)
            ->line('Thank you for using 🚀'.\config('other.title'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Max Hit and Run Warnings Reached!',
            'body'  => 'You have hit the limit on active Hit and Run Warnings! Your download privilliges have been revoked!',
            'url'   => \sprintf('/users/%s', $this->user->username),
        ];
    }
}
