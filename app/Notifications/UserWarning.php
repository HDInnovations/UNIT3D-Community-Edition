<?php

namespace App\Notifications;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserWarning extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Torrent $torrent)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $profileUrl = \href_profile($this->user);

        return (new MailMessage())
            ->greeting('Hit and Run Warning!')
            ->line('You have revieved a hit and run warning on one or more torrents!')
            ->action('View Unsatfied Torrents to seed off your warnings or wait until they expire!', $profileUrl)
            ->line('Thank you for using 🚀'.\config('other.title'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => $this->torrent->name.' Warning Recieved',
            'body'  => 'You have received an automated WARNING from the system because you failed to follow the Hit and Run rules in relation to Torrent '.$this->torrent->name,
            'url'   => \sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
