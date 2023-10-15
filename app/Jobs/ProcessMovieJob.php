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

use App\Enums\Occupations;
use App\Models\Collection;
use App\Models\Company;
use App\Models\Credit;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Recommendation;
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

    /**
     * ProcessMovieJob constructor.
     */
    public function __construct(public $movie)
    {
    }

    public function handle(): void
    {
        $tmdb = new TMDB();
        $movie = Movie::find((int) $this->movie['id']);

        // Genres

        $genres = [];
        $genre_ids = [];

        foreach ($this->movie['genres'] as $genre) {
            $genre_ids[] = $genre['id'];
            $genres[] = [
                'id'   => $genre['id'],
                'name' => $genre['name']
            ];
        }

        Genre::upsert($genres, 'id');
        $movie->genres()->sync(array_unique($genre_ids));

        // Companies

        $companies = [];
        $company_ids = [];

        foreach ($this->movie['production_companies'] ?? [] as $company) {
            $company = (new Client\Company($company['id']))->getData();

            $company_ids[] = $company['id'];
            $companies[] = [
                'id'             => $company['id'],
                'description'    => $company['description'] ?? null,
                'headquarters'   => $company['headquarters'] ?? null,
                'homepage'       => $company['homepage'] ?? null,
                'logo'           => $tmdb->image('logo', $company),
                'name'           => $company['name'] ?? null,
                'origin_country' => $company['origin_country'],
            ];
        }

        Company::upsert($companies, 'id');
        $movie->companies()->sync(array_unique($company_ids));

        // Collection

        if (isset($this->movie['belongs_to_collection']['id'])) {
            $collection = (new Client\Collection($this->movie['belongs_to_collection']['id']))->getData();

            $titleSort = addslashes(str_replace(['The ', 'An ', 'A ', '"'], [''], (string) $collection['name']));

            $collection = [
                'id'        => $collection['id'],
                'name'      => $collection['name'] ?? null,
                'name_sort' => $titleSort,
                'parts'     => is_countable($collection['parts']) ? \count($collection['parts']) : 0,
                'overview'  => $collection['overview'] ?? null,
                'poster'    => $tmdb->image('poster', $collection),
                'backdrop'  => $tmdb->image('backdrop', $collection),
            ];

            Collection::upsert($collection, 'id');
            $movie->collection()->sync([$collection['id']]);
        }

        // People

        $people_ids = [];
        $credits = [];

        foreach ($this->movie['credits']['cast'] ?? [] as $person) {
            $credits[] = [
                'movie_id'      => $this->movie['id'],
                'person_id'     => $person['id'],
                'occupation_id' => Occupations::ACTOR->value,
                'character'     => $person['character'] ?? '',
                'order'         => $person['order'] ?? null
            ];
            $people_ids[] = $person['id'];
        }

        foreach ($this->movie['credits']['crew'] ?? [] as $person) {
            $job = Occupations::from_tmdb_job($person['job']);

            if ($job !== null) {
                $credits[] = [
                    'movie_id'      => $this->movie['id'],
                    'person_id'     => $person['id'],
                    'occupation_id' => $job->value,
                    'character'     => null,
                    'order'         => null
                ];
                $people_ids[] = $person['id'];
            }
        }

        $people = [];

        foreach (array_unique($people_ids) as $person_id) {
            $person = (new Client\Person($person_id))->getData();
            $people[] = $tmdb->person_array($person);
        }

        Person::upsert($people, 'id');
        Credit::where('movie_id', '=', $this->movie['id'])->delete();
        Credit::upsert($credits, ['person_id', 'movie_id', 'tv_id', 'occupation_id', 'character']);

        // Recommendations

        $movie_ids = Movie::select('id')->findMany(array_column($this->movie['recommendations']['results'] ?? [], 'id'))->pluck('id');
        $recommendations = [];

        foreach ($this->movie['recommendations']['results'] ?? [] as $recommendation) {
            if ($movie_ids->contains($recommendation['id'])) {
                $recommendations[] = [
                    'recommendation_movie_id' => $recommendation['id'],
                    'movie_id'                => $this->movie['id'],
                    'title'                   => $recommendation['title'],
                    'vote_average'            => $recommendation['vote_average'],
                    'poster'                  => $tmdb->image('poster', $recommendation),
                    'release_date'            => $recommendation['release_date'],
                ];
            }
        }

        Recommendation::upsert($recommendations, ['recommendation_movie_id', 'movie_id']);
    }
}
