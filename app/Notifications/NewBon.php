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
 * @author     HDVinnie, singularity43
 */

namespace App\Notifications;

use App\Models\BonTransactions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class NewBon extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $sender;

    /**
     * @var \App\Models\BonTransactions
     */
    public BonTransactions $transaction;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  string  $sender
     * @param  BonTransactions  $transaction
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     */
    public function __construct(string $type, string $sender, BonTransactions $transaction, Repository $configRepository)
    {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->sender = $sender;
        $this->configRepository = $configRepository;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function toArray($notifiable): array
    {
        $appurl = $this->configRepository->get('app.url');

        return [
            'title' => $this->sender.' Has Gifted You '.$this->transaction->cost.' BON',
            'body'  => $this->sender.' has gifted you '.$this->transaction->cost.' BON with the following note: '.$this->transaction->comment,
            'url'   => sprintf('/users/%s', $this->transaction->senderObj->username),
        ];
    }
}
