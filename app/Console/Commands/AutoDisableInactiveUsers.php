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

use App\Jobs\SendDisableUserMail;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Events\Dispatcher;

final class AutoDisableInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'auto:disable_inactive_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'User account must be at least x days old & user account x days Of inactivity to be disabled';
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    private $eventDispatcher;

    public function __construct(Repository $configRepository, Dispatcher $eventDispatcher)
    {
        $this->configRepository = $configRepository;
        parent::__construct();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        if ($this->configRepository->get('pruning.user_pruning') == true) {
            $disabled_group = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));

            $current = Carbon::now();

            $matches = User::whereIn('group_id', [$this->configRepository->get('pruning.group_ids')]);

            $users = $matches->where('created_at', '<', $current->copy()->subDays($this->configRepository->get('pruning.account_age'))->toDateTimeString())
                ->where('last_login', '<', $current->copy()->subDays($this->configRepository->get('pruning.last_login'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                if ($user->getSeeding() !== 0) {
                    $user->group_id = $disabled_group[0];
                    $user->can_upload = 0;
                    $user->can_download = 0;
                    $user->can_comment = 0;
                    $user->can_invite = 0;
                    $user->can_request = 0;
                    $user->can_chat = 0;
                    $user->disabled_at = Carbon::now();
                    $user->save();

                    // Send Email
                    $this->eventDispatcher->dispatch(new SendDisableUserMail($user));
                }
            }
        }
    }
}
