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

        foreach ($this->movie['genres'] as $genre) {
            if (isset($genre['name'])) {
                Genre::updateOrCreate(['id' => $genre['id']], $genre)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        foreach ($this->movie['production_companies'] as $productionCompany) {
            $client = new Client\Company($productionCompany['id']);
            $productionCompany = $client->getData();

            if (isset($productionCompany['name'])) {
                $productionCompanyArray = [
                    'description'    => $productionCompany['description'] ?? null,
                    'headquarters'   => $productionCompany['headquarters'] ?? null,
                    'homepage'       => $productionCompany['homepage'] ?? null,
                    'logo'           => $tmdb->image('logo', $productionCompany),
                    'name'           => $productionCompany['name'] ?? null,
                    'origin_country' => $productionCompany['origin_country'],
                ];
                Company::updateOrCreate(['id' => $productionCompany['id']], $productionCompanyArray)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        if (isset($this->movie['belongs_to_collection']['id'])) {
            $client = new Client\Collection($this->movie['belongs_to_collection']['id']);
            $belongsToCollection = $client->getData();
            if (isset($belongsToCollection['name'])) {
                $titleSort = \addslashes(\str_replace(['The ', 'An ', 'A ', '"'], [''], $belongsToCollection['name']));

                $belongsToCollectionArray = [
                    'name'      => $belongsToCollection['name'] ?? null,
                    'name_sort' => $titleSort,
                    'parts'     => is_countable($belongsToCollection['parts']) ? \count($belongsToCollection['parts']) : 0,
                    'overview'  => $belongsToCollection['overview'] ?? null,
                    'poster'    => $tmdb->image('poster', $belongsToCollection),
                    'backdrop'  => $tmdb->image('backdrop', $belongsToCollection),
                ];
                Collection::updateOrCreate(['id' => $belongsToCollection['id']], $belongsToCollectionArray)->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        if (isset($this->movie['credits']['cast'])) {
            foreach ($this->movie['credits']['cast'] as $cast) {
                Cast::updateOrCreate(['id' => $cast['id']], $tmdb->cast_array($cast))->movie()->syncWithoutDetaching([$this->movie['id']]);
                Person::updateOrCreate(['id' => $cast['id']], $tmdb->person_array($cast))->movie()->syncWithoutDetaching([$this->movie['id']]);
            }
        }

        if (isset($this->movie['credits']['crew'])) {
            foreach ($this->movie['credits']['crew'] as $crew) {
                Crew::updateOrCreate(['id' => $crew['id']], $tmdb->person_array($crew))
                    ->movie()
                    ->syncWithoutDetaching([$this->movie['id'] => [
                        'department' => $crew['department'] ?? null,
                        'job'        => $crew['job'] ?? null,
                    ]]);
            }
        }

        if (isset($this->movie['recommendations'])) {
            foreach ($this->movie['recommendations']['results'] as $recommendation) {
                if (Movie::where('id', '=', $recommendation['id'])->count() !== 0) {
                    Recommendation::updateOrCreate(
                        ['recommendation_movie_id' => $recommendation['id'], 'movie_id' => $this->movie['id']],
                        ['title' => $recommendation['title'], 'vote_average' => $recommendation['vote_average'], 'poster' => $tmdb->image('poster', $recommendation), 'release_date' => $recommendation['release_date']]
                    );
                }
            }
        }
    }
}
