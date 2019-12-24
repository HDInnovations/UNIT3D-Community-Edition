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

use App\Jobs\SendDeleteUserMail;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Events\Dispatcher;

final class AutoSoftDeleteDisabledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'auto:softdelete_disabled_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'User account must be In disabled group for at least x days';
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
            $pruned_group = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

            $current = Carbon::now();
            $users = User::where('group_id', '=', $disabled_group[0])
                ->where('disabled_at', '<', $current->copy()->subDays($this->configRepository->get('pruning.soft_delete'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                // Send Email
                $this->eventDispatcher->dispatch(new SendDeleteUserMail($user));

                $user->can_upload = 0;
                $user->can_download = 0;
                $user->can_comment = 0;
                $user->can_invite = 0;
                $user->can_request = 0;
                $user->can_chat = 0;
                $user->group = $pruned_group[0];
                $user->deleted_by = 1;
                $user->save();
                $user->delete();
            }
        }
    }
}
