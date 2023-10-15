<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoStepAuthCode extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * TwoStepAuthCode Constructor.
     */
    public function __construct(protected User $user, protected string $code)
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
            ->from(config('auth.verificationEmailFrom'), config('auth.verificationEmailFromName'))
            ->subject(trans('auth.verificationEmailSubject'))
            ->greeting(trans('auth.verificationEmailGreeting', ['username' => $this->user->username]))
            ->line(trans('auth.verificationEmailMessage'))
            ->line($this->code)
            ->action(trans('auth.verificationEmailButton'), route('verificationNeeded'));
    }
}
