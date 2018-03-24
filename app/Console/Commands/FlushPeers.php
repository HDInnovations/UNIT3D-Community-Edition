<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\History;
use App\Peer;

class FlushPeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FlushPeers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flushes ghost peers every hour.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Peer::chunk(250, function ($section) {
            foreach ($section as $data) {
                if ((time() - strtotime($data->updated_at)) > (60 * 60 * 2)) {
                    History::where("info_hash", $data->hash)->where("user_id", $data->user_id)->update(['active' => false]);
                    $data->delete();
                }
            }
        });
    }
}
