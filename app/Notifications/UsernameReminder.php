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

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Translation\Translator;

final class UsernameReminder extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private Translator $translator;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * Create a new notification instance.
     *
     * UsernameReminderEmail constructor.
     *
     * @param  \Illuminate\Translation\Translator  $translator
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     * @param  \Illuminate\Routing\UrlGenerator  $urlGenerator
     */
    public function __construct(Translator $translator, Repository $configRepository, UrlGenerator $urlGenerator)
    {
        $this->translator = $translator;
        $this->configRepository = $configRepository;
        $this->urlGenerator = $urlGenerator;
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                    ->subject($this->translator->trans('common.your').' '.$this->configRepository->get('app.name').' '.$this->translator->trans('common.username'))
                    ->greeting($this->translator->trans('common.contact-header').', '.$notifiable->username)
                    ->line($this->translator->trans('email.username-reminder').' '.$notifiable->username)
                    ->action('Login as '.$notifiable->username, $this->urlGenerator->route('login'))
                    ->line($this->translator->trans('email.thanks').' '.$this->configRepository->get('app.name'));
    }
}
