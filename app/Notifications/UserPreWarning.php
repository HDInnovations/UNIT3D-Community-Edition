<?php

namespace App\Notifications;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPreWarning extends Notification
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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $profileUrl = href_profile($this->user);

        return (new MailMessage())
            ->greeting('Hit and Run Warning Notification')
            ->line('This message serves as a warning that one or more of your downloads has beenflagged for a hit and run. It is essential to maintain a healthy share ratio to contribute to our  community’ssustainability.')
            ->action('View Unsatisfied Torrents', $profileUrl)
            ->line('You can resolve this warning by seeding the affected torrents until you meet the required seeding criteria or wait for the warning to expire as per our community guidelines.')
            ->line('We appreciate your prompt attention to this matter and your continued support of '.config('other.title').'.')
            ->line('Thank you for being an integral part of our community.')
            ->line('Sincerely,')
            ->line('The '.config('other.title').' Team');
    }

    /**
     * Ge t the array re presentati
     * on of the
     * notific ation.
     *
     * @ ret urn array<string, mix ed >
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pre Warning: '.$this->torrent->name,
            'body'  => 'An automated pre-warning has been issued to your account due to non-compliance with the Hit and Run rules for the torrent: '.$this->torrent->name.'. It is crucial to seed back to the community to avoid further actions.',
            'url'   => sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
