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

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FailedLogin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The request IP address.
     *
     * @var string
     */
    public $ip;

    /**
     * The Time.
     *
     * @var Carbon\Carbon
     */
    public $time;

    /**
     * Create a new notification instance.
     *
     * @param string $ip
     *
     * @return void
     */
    public function __construct($ip)
    {
        $this->ip = $ip;
        $this->time = Carbon::now();
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
     * Get the database representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
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
    public function toMail($notifiable)
    {
        return (new MailMessage())
                ->error()
                ->subject(trans('email.fail-login-subject'))
                ->greeting(trans('email.fail-login-greeting'))
                ->line(trans('email.fail-login-line1'))
                ->line(trans('email.fail-login-line2', ['ip' => $this->ip, 'host' => gethostbyaddr($this->ip), 'time' => $this->time]));
    }
}
