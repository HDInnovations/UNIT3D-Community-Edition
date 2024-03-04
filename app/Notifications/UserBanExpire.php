<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBanExpire extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
        return (new MailMessage())
            ->greeting('Notice of Account Reinstatement')
            ->line('We are pleased to inform you that your account on '.config('other.title').' has been reinstated.')
            ->line('We appreciate your understanding and cooperation with our community guidelines. We hope you continue to enjoy and contribute positively to '.config('other.title').'.')
            ->line('Thank you for being a valued member of our community.')
            ->line('Sincerely,')
            ->line('The '.config('other.title').' Team');
    }
}
