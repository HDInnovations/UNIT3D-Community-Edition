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
        $chatdUrl = config('unit3d.chat-link-url');

        return (new MailMessage())
            ->greeting('Account Suspension Notice')
            ->line('This is to inform you that your account on ' . config('other.title') . ' has been suspended indefinitely. The reason for this action is as follows: ' . $this->ban->ban_reason)
            ->line('This decision reflects our commitment to maintaining a respectful and safe community environment. Violation of our terms has serious consequences, and we enforce these rules to protect our community.')
            ->action('Appeal Process', $chatdUrl)
            ->line('If you believe this to be a mistake or wish to discuss this matter, you may appeal the decision through our IRC channel. Note, however, that this does not guarantee reinstatement.')
            ->line('We take these matters very seriously, and we urge you to reflect on the reasons provided for your suspension. Continued disregard for our community standards will result in permanent exclusion.')
            ->line('We regret having to take such measures but deem it necessary to ensure the integrity and safety of ' . config('other.title') . '.')
            ->line('Sincerely,')
            ->line('The ' . config('other.title') . ' Team');
    }
}
