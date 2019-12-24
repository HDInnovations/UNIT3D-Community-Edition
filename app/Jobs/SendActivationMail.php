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

namespace App\Jobs;

use App\Mail\ActivateUser;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendActivationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * @var string
     */
    public string $code;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;
    /**
     * @var \Illuminate\Mail\Mailer
     */
    private Mailer $mailer;

    /**
     * ActivateUser constructor.
     *
     * @param  User  $user
     * @param  string  $code
     * @param  \Illuminate\Mail\Mailer  $mailer
     */
    public function __construct(User $user, string $code, Mailer $mailer)
    {
        $this->user = $user;
        $this->code = $code;
        $this->mailer = $mailer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->attempts() > 2) {
            $this->delay(min(30 * $this->attempts(), 300));
        }

        $this->mailer->to($this->user)->send(new ActivateUser($this->user, $this->code));
    }
}
