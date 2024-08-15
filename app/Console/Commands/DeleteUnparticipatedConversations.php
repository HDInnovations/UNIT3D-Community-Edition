<?php

declare(strict_types=1);

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

use App\Models\Conversation;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnparticipatedConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:delete_unparticipated_conversations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes conversation where all users have deleted their participation';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $start = now();

        $deletedParticipants = Participant::query()->where('user_id', '=', User::SYSTEM_USER_ID)->delete();

        $deletedConversations = Conversation::query()->whereDoesntHave('participants')->delete();

        $elapsed = now()->floatDiffInSeconds($start);

        $this->info("Deleted {$deletedParticipants} participants and {$deletedConversations} conversations in {$elapsed} seconds");
    }
}
