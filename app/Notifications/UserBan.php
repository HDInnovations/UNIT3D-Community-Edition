<?php

namespace App\Notifications;

use App\Models\Ban;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBan extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Ban $ban)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $chatdUrl = config('unit3d.support-link-url');

        return (new MailMessage())
            ->greeting('Notice of Account Suspension')
            ->line(
                'Your account on '.config(
                    'other.title'
                ).' has been suspended due to the following reason: '.$this->ban->ban_reason.'.'
            )
            ->action('Request Support', $chatdUrl)
            ->line(
                'We value every member of our community and understand that misunderstandings can occur. We encourage you to reach out for support if you believe this suspension to be in error or if you wish to discuss the matter further.'
            )
            ->line('Thank you for your understanding and cooperation.');
    }
}
