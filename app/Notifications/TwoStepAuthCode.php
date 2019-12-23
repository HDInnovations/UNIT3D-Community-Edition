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

use Illuminate\Contracts\Config\Repository;
use Illuminate\Translation\Translator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TwoStepAuthCode extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    protected $user;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;
    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    private $urlGenerator;

    /**
     * Create a new notification instance.
     * @param $user
     * @param $code
     */
    public function __construct($user, $code, Repository $configRepository, Translator $translator, UrlGenerator $urlGenerator)
    {
        $this->code = $code;
        $this->user = $user;
        $this->configRepository = $configRepository;
        $this->translator = $translator;
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
            ->from($this->configRepository->get('auth.verificationEmailFrom'), $this->configRepository->get('auth.verificationEmailFromName'))
            ->subject($this->translator->trans('auth.verificationEmailSubject'))
            ->greeting($this->translator->trans('auth.verificationEmailGreeting', ['username' => $this->user->name]))
            ->line($this->translator->trans('auth.verificationEmailMessage'))
            ->line($this->code)
            ->action($this->translator->trans('auth.verificationEmailButton'), $this->urlGenerator->route('verificationNeeded'));
    }
}
