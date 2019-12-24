<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Translation\Translator;

final class FailedLogin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The request IP address.
     *
     * @var string
     */
    public string $ip;

    /**
     * The Time.
     *
     * @var \Carbon\Carbon
     */
    public Carbon $time;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    /**
     * Create a new notification instance.
     *
     * @param  string  $ip
     *
     * @param  \Illuminate\Translation\Translator  $translator
     */
    public function __construct(string $ip, Translator $translator)
    {
        $this->ip = $ip;
        $this->time = Carbon::now();
        $this->translator = $translator;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param mixed  $notifiable
     * @return Carbon\Carbon\Carbon[]|string[]
     */
    public function toArray($notifiable): array
    {
        return [
            'ip'   => $this->ip,
            'time' => $this->time,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                ->error()
                ->subject($this->translator->trans('email.fail-login-subject'))
                ->greeting($this->translator->trans('email.fail-login-greeting'))
                ->line($this->translator->trans('email.fail-login-line1'))
                ->line($this->translator->trans('email.fail-login-line2', ['ip' => $this->ip, 'host' => gethostbyaddr($this->ip), 'time' => $this->time]));
    }
}
