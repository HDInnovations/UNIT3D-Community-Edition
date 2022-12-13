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
        $chatdUrl = \config('unit3d.chat-link-url');

        return (new MailMessage())
            ->greeting('You have been banned 😭')
            ->line('You have been banned from '.\config('other.title').' for '.$this->ban->ban_reason)
            ->action('Need Support?', $chatdUrl)
            ->line('Thank you for using 🚀'.\config('other.title'));
    }
}
