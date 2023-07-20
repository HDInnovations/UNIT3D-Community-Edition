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
use App\Models\Company;
use App\Models\Credit;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Network;
use App\Models\Person;
use App\Models\Recommendation;
use App\Models\Season;
use App\Models\Tv;
use App\Services\Tmdb\Client;
use App\Services\Tmdb\TMDB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessTvJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * ProcessTvJob Constructor.
     */
    public function __construct(public $tv, public $id)
    {
    }

    public function handle(): void
    {
        $tmdb = new TMDB();
        $tv = Tv::find((int) $this->tv['id']);

        // Companies

        $companies = [];
        $company_ids = [];

        foreach ($this->tv['production_companies'] ?? [] as $company) {
            $companies[] = [
                'id'             => $company['id'],
                'description'    => $tmdb->ifExists('description', $company),
                'name'           => $company['name'],
                'headquarters'   => $tmdb->ifExists('headquarters', $company),
                'homepage'       => $tmdb->ifExists('homepage', $company),
                'logo'           => $tmdb->image('logo', $company),
                'origin_country' => $tmdb->ifExists('origin_country', $company),
            ];
            $company_ids[] = $company['id'];
        }

        Company::upsert($companies, 'id');
        $tv->companies()->sync(array_unique($company_ids));

        // Networks

        $networks = [];
        $network_ids = [];

        foreach ($this->tv['networks'] ?? [] as $network) {
            $network = (new Client\Network($network['id']))->getData();

            if (isset($network['id'], $network['name'])) {
                if (isset($network['images']['logos'][0]) && \array_key_exists('file_path', $network['images']['logos'][0])) {
                    $logo = 'https://image.tmdb.org/t/p/original'.$network['images']['logos'][0]['file_path'];
                } else {
                    $logo = null;
                }

                $networks[] = [
                    'id'             => $network['id'],
                    'headquarters'   => $tmdb->ifExists('headquarters', $network),
                    'homepage'       => $tmdb->ifExists('homepage', $network),
                    'logo'           => $logo,
                    'name'           => $network['name'],
                    'origin_country' => $network['origin_country'],
                ];
                $network_ids[] = $network['id'];
            }
        }

        Network::upsert($networks, 'id');
        $tv->networks()->sync(array_unique($network_ids));

        // Genres

        $genres = [];
        $genre_ids = [];

        foreach ($this->tv['genres'] as $genre) {
            $genres[] = [
                'id'   => $genre['id'],
                'name' => $genre['name']
            ];
            $genre_ids[] = $genre['id'];
        }

        Genre::upsert($genres, 'id');
        $tv->genres()->sync(array_unique($genre_ids));

        // People

        $credits = [];
        $people_ids = [];

        foreach ($this->tv['aggregate_credits']['cast'] ?? [] as $person) {
            foreach ($person['roles'] as $role) {
                $credits[] = [
                    'tv_id'         => $this->tv['id'],
                    'person_id'     => $person['id'],
                    'occupation_id' => Occupations::ACTOR->value,
                    'character'     => $role['character'] ?? '',
                    'order'         => $person['order'] ?? null
                ];
                $people_ids[] = $person['id'];
            }
        }

        foreach ($this->tv['aggregate_credits']['crew'] ?? [] as $person) {
            foreach ($person['jobs'] as $job) {
                $occupation = Occupations::from_tmdb_job($job['job']);

                if ($occupation !== null) {
                    $credits[] = [
                        'tv_id'         => $this->tv['id'],
                        'person_id'     => $person['id'],
                        'occupation_id' => $occupation->value,
                        'character'     => null,
                        'order'         => null,
                    ];
                    $people_ids[] = $person['id'];
                }
            }
        }

        foreach ($this->tv['created_by'] ?? [] as $person) {
            $credits[] = [
                'tv_id'         => $this->tv['id'],
                'person_id'     => $person['id'],
                'occupation_id' => Occupations::CREATOR->value,
                'character'     => null,
                'order'         => null,
            ];
            $people_ids[] = $person['id'];
        }

        $people = [];

        foreach (array_unique($people_ids) as $person_id) {
            $person = (new Client\Person($person_id))->getData();
            $people[] = $tmdb->person_array($person);
        }

        Person::upsert($people, 'id');
        Credit::where('tv_id', '=', $this->tv['id'])->delete();
        Credit::upsert($credits, ['person_id', 'movie_id', 'tv_id', 'occupation_id', 'character']);

        // Seasons and episodes

        $seasons = [];
        $episodes = [];

        foreach ($this->tv['seasons'] as $season) {
            $season = (new Client\Season($this->id, $season['season_number']))->getData();

            $seasons[] = [
                'id'            => $season['id'],
                'air_date'      => $tmdb->ifExists('air_date', $season),
                'poster'        => $tmdb->image('poster', $season),
                'name'          => $tmdb->ifExists('name', $season),
                'overview'      => $tmdb->ifExists('overview', $season),
                'season_number' => $season['season_number'],
                'tv_id'         => $this->id,
            ];

            foreach ($season['episodes'] as $episode) {
                $episodes[] = [
                    'id'              => $episode['id'],
                    'tv_id'           => $this->id,
                    'air_date'        => $tmdb->ifExists('air_date', $episode),
                    'name'            => Str::limit($tmdb->ifExists('name', $episode), 200),
                    'episode_number'  => $episode['episode_number'],
                    'overview'        => $tmdb->ifExists('overview', $episode),
                    'still'           => $tmdb->image('still', $episode),
                    'production_code' => $episode['production_code'],
                    'season_number'   => $episode['season_number'],
                    'vote_average'    => $episode['vote_average'],
                    'vote_count'      => $episode['vote_count'],
                    'season_id'       => $season['id'],
                ];
            }
        }

        Season::upsert($seasons, 'id');
        Episode::upsert($episodes, 'id');

        // Recommendations

        $tv_ids = Tv::select('id')->findMany(array_column($this->tv['recommendations']['results'] ?? [], 'id'))->pluck('id');
        $recommendations = [];

        foreach ($this->tv['recommendations']['results'] ?? [] as $recommendation) {
            if ($tv_ids->contains($recommendation['id'])) {
                $recommendations[] = [
                    'recommendation_tv_id' => $recommendation['id'],
                    'tv_id'                => $this->tv['id'],
                    'title'                => $recommendation['name'],
                    'vote_average'         => $recommendation['vote_average'],
                    'poster'               => $tmdb->image('poster', $recommendation),
                    'first_air_date'       => $recommendation['first_air_date'],
                ];
            }
        }

        Recommendation::upsert($recommendations, ['recommendation_tv_id', 'tv_id']);
    }
}
