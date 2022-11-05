<?php

namespace App\Notifications;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserWarningExpire extends Notification
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
            ->greeting('Hit and Run Warning Expired!')
            ->line('Your Hit and Run Warning has expired or been seeded off!')
            ->action('View Profile!', $profileUrl)
            ->line('Thank you for using 🚀'.\config('other.title'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => $this->torrent->name.' Hit and Run Warning Expired',
            'body'  => 'Your Hit and Run Warning has expired or been seeded off on '.$this->torrent->name,
            'url'   => \sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
