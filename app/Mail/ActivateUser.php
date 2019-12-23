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
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ActivateUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * @var string
     */
    public string $code;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * ActivateUser constructor.
     *
     * @param User   $user
     * @param string $code
     */
    public function __construct(User $user, string $code, Repository $configRepository)
    {
        $this->user = $user;
        $this->code = $code;
        $this->configRepository = $configRepository;
    }

    public function build(): self
    {
        return $this->markdown('emails.activate')
            ->subject('Activation Required '.$this->configRepository->get('other.title'));
    }
}
