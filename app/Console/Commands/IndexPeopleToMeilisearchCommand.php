<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;
use Meilisearch\Client;

class IndexPeopleToMeilisearchCommand extends Command
{
    protected $signature = 'index:people-to-meilisearch';

    protected $description = 'Command description';

    public function handle(): void
    {
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

        $this->info('People indexed successfully.');
    }
}
