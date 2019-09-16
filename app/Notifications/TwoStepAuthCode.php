<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TwoStepAuthCode extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    protected $user;

    /**
     * Create a new notification instance.
     * @param $user
     * @param $code
     */
    public function __construct($user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->from(config('auth.verificationEmailFrom'), config('auth.verificationEmailFromName'))
            ->subject(trans('auth.verificationEmailSubject'))
            ->greeting(trans('auth.verificationEmailGreeting', ['username' => $this->user->name]))
            ->line(trans('auth.verificationEmailMessage'))
            ->line($this->code)
            ->action(trans('auth.verificationEmailButton'), route('verificationNeeded'));
    }
}
