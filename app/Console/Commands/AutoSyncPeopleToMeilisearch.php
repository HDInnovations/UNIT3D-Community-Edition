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
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\Person;
use Exception;
use Illuminate\Console\Command;
use Meilisearch\Client;

class AutoSyncPeopleToMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sync_people_to_meilisearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs people to Meilisearch';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $start = now();

        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
        $index = $client->index('people');

        $people = Person::all(['id', 'name', 'birthday', 'still']);

        $documents = $people->map(fn ($person) => [
            'id'       => $person->id,
            'name'     => $person->name,
            'birthday' => $person->birthday,
            'still'    => $person->still,
        ])->toArray();

        $index->addDocuments($documents);

        $this->comment('Synced all people to Meilisearch in '.(now()->diffInMilliseconds($start) / 1000).' seconds.');
    }
}
