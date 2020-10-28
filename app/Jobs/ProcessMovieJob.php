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

use App\Models\Cast;
use App\Models\Collection;
use App\Models\Company;
use App\Models\Crew;
use App\Models\Genre;
use App\Models\Person;
use App\Services\Tmdb\Client;
use App\Services\Tmdb\TMDB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMovieJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    public function handle()
    {
        $helper = new TMDB();

        foreach ($this->movie['genres'] as $genre) {
            if (isset($genre['name'])) {
                Genre::updateOrCreate(['id' => $genre['id']], $genre)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        foreach ($this->movie['production_companies'] as $production_company) {
            $client = new Client\Company($production_company['id']);
            $production_company = $client->index();

            if (isset($production_company['name'])) {
                $production_company_array = [
                    'description'    => $production_company['description'] ?? null,
                    'headquarters'   => $production_company['headquarters'] ?? null,
                    'homepage'       => $production_company['homepage'] ?? null,
                    'logo'           => $helper->image('logo', $production_company),
                    'name'           => $production_company['name'] ?? null,
                    'origin_country' => $production_company['origin_country'],
                ];
                Company::updateOrCreate(['id' => $production_company['id']], $production_company_array)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        if (isset($this->movie['belongs_to_collection']['id'])) {
            $client = new Client\Collection($this->movie['belongs_to_collection']['id']);
            $belongs_to_collection = $client->index();
            if (isset($belongs_to_collection['name'])) {
                $titleSort = addslashes(str_replace(['The ', 'An ', 'A ', '"'], [''], $belongs_to_collection['name']));

                $belongs_to_collection_array = [
                    'name'      => $belongs_to_collection['name'] ?? null,
                    'name_sort' => $titleSort,
                    'parts'     => count($belongs_to_collection['parts']),
                    'overview'  => $belongs_to_collection['overview'] ?? null,
                    'poster'    => $helper->image('poster', $belongs_to_collection),
                    'backdrop'  => $helper->image('backdrop', $belongs_to_collection),
                ];
                Collection::updateOrCreate(['id' => $belongs_to_collection['id']], $belongs_to_collection_array)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        foreach ($this->movie['credits']['cast'] as $person) {
            if (isset($person['id'])) {
                Cast::updateOrCreate(['id' => $person['id']], $helper->cast_array($person))->movie()->syncWithoutDetaching([$this->movie['id']]);
                Person::updateOrCreate(['id' => $person['id']], $helper->person_array($person))->movie()->syncWithoutDetaching([$this->movie['id']]);

                $client = new Client\Person($person['id']);
                $people = $client->index();
                Crew::updateOrCreate(['id' => $people['id']], $helper->person_array($people))->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }
    }
}
