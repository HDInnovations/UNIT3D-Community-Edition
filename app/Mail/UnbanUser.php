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

namespace App\Mail;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class UnbanUser extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $ban;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Create a new message instance.
     *
     * @param $email
     * @param $ban
     */
    public function __construct($email, $ban, Repository $configRepository)
    {
        $this->email = $email;
        $this->ban = $ban;
        $this->configRepository = $configRepository;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->markdown('emails.unban')
            ->subject('You Have Been Unbanned - '.$this->configRepository->get('other.title'));
    }
}
