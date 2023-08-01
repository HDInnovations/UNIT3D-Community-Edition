<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\Peer;
use Illuminate\Console\Command;
use Exception;

class AutoDeleteStoppedPeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:delete_stopped_peers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all stopped peers';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        Peer::where('active', '=', 0)->where('updated_at', '>', now()->subHours(2))->delete();

        $this->comment('Automated delete stopped peers command complete');
    }
}
