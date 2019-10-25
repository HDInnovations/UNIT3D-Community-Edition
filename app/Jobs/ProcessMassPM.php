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

use Illuminate\Bus\Queueable;
use App\Models\PrivateMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMassPM implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender_id;

    protected $receiver_id;

    protected $subject;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @param $sender_id
     * @param $receiver_id
     * @param $subject
     * @param $message
     */
    public function __construct($sender_id, $receiver_id, $subject, $message)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pm = new PrivateMessage();
        $pm->sender_id = $this->sender_id;
        $pm->receiver_id = $this->receiver_id;
        $pm->subject = $this->subject;
        $pm->message = $this->message;
        $pm->save();
    }
}
