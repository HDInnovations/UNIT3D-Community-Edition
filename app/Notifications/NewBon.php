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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewBon extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $sender;

    /**
     * @var \App\Models\BonTransactions
     */
    public $transaction;

    /**
     * Create a new notification instance.
     *
     * @param string          $type
     * @param string          $sender
     * @param BonTransactions $transaction
     */
    public function __construct(string $type, string $sender, BonTransactions $transaction)
    {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
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
     *
     * @return string[]
     */
    public function toArray($notifiable): array
    {
        $appurl = config('app.url');

        return [
            'title' => $this->sender.' Has Gifted You '.$this->transaction->cost.' BON',
            'body'  => $this->sender.' has gifted you '.$this->transaction->cost.' BON with the following note: '.$this->transaction->comment,
            'url'   => sprintf('/users/%s', $this->transaction->senderObj->username),
        ];
    }
}
