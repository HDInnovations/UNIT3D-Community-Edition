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

use App\Models\Collection;
use App\Models\Company;
use App\Models\Credit;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Recommendation;
use App\Services\Tmdb\Client;
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
    public function __construct(public int $id)
    {
    }

    public function handle(): void
    {
        // Movie

        $movieScraper = new Client\Movie($this->id);

        $movie = Movie::updateOrCreate(['id' => $this->id], $movieScraper->getMovie());

        // Genres

        Genre::upsert($movieScraper->getGenres(), 'id');
        $movie->genres()->sync(array_unique(array_column($movieScraper->getGenres(), 'id')));

        // Companies

        $companies = [];

        foreach ($movieScraper->data['production_companies'] ?? [] as $company) {
            $companies[] = (new Client\Company($company['id']))->getCompany();
        }

        Company::upsert($companies, 'id');
        $movie->companies()->sync(array_unique(array_column($companies, 'id')));

        // Collection

        if ($movieScraper->data['belongs_to_collection'] !== null) {
            $collection = (new Client\Collection($movieScraper->data['belongs_to_collection']['id']))->getCollection();

            Collection::upsert($collection, 'id');
            $movie->collection()->sync([$collection['id']]);
        }

        // People

        $credits = $movieScraper->getCredits();
        $people = [];

        foreach (array_unique(array_column($credits, 'person_id')) as $person_id) {
            $people[] = (new Client\Person($person_id))->getPerson();
        }

        Person::upsert($people, 'id');
        Credit::where('movie_id', '=', $this->id)->delete();
        Credit::upsert($credits, ['person_id', 'movie_id', 'tv_id', 'occupation_id', 'character']);

        // Recommendations

        Recommendation::upsert($movieScraper->getRecommendations(), ['recommendation_movie_id', 'movie_id']);
    }
}
