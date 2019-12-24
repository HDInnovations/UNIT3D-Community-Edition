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

namespace App\Console\Commands;

use App\Models\Audit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

final class AutoRecycleAudits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'auto:recycle_activity_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Recycle Activity From Log Once 30 Days Old.';
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    public function __construct(Repository $configRepository)
    {
        $this->configRepository = $configRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $current = Carbon::now();
        $audits = Audit::where('created_at', '<', $current->copy()->subDays($this->configRepository->get('audit.recycle'))->toDateTimeString())->get();

        foreach ($audits as $audit) {
            $audit->delete();
        }
    }
}
