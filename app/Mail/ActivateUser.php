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
     * ActivateUser constructor.
     *
     * @param User   $user
     * @param string $code
     */
    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    public function build(): self
    {
        return $this->markdown('emails.activate')
            ->subject('Activation Required '.config('other.title'));
    }
}
