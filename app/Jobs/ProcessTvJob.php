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

        foreach ($this->tv['production_companies'] as $productionCompany) {
            if (isset($productionCompany['name'])) {
                $productionCompanyArray = [
                    'description'    => $tmdb->ifExists('description', $productionCompany),
                    'name'           => $productionCompany['name'],
                    'headquarters'   => $tmdb->ifExists('headquarters', $productionCompany),
                    'homepage'       => $tmdb->ifExists('homepage', $productionCompany),
                    'logo'           => $tmdb->image('logo', $productionCompany),
                    'origin_country' => $tmdb->ifExists('origin_country', $productionCompany),
                ];
                Company::updateOrCreate(['id' => $productionCompany['id']], $productionCompanyArray)->tv()->syncWithoutDetaching([$this->tv['id']]);
            }
        }

        foreach ($this->tv['networks'] as $network) {
            $client = new Client\Network($network['id']);
            $network = $client->getData();

            if (isset($network['name'])) {
                if (isset($network['images']['logos'][0]) && \array_key_exists('file_path', $network['images']['logos'][0])) {
                    $logo = 'https://image.tmdb.org/t/p/original'.$network['images']['logos'][0]['file_path'];
                } else {
                    $logo = null;
                }

                $networkArray = [
                    'headquarters'   => $tmdb->ifExists('headquarters', $network),
                    'homepage'       => $tmdb->ifExists('homepage', $network),
                    'logo'           => $logo,
                    'name'           => $network['name'],
                    'origin_country' => $network['origin_country'],
                ];
                Network::updateOrCreate(['id' => $network['id']], $networkArray)->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['genres'] as $genre) {
            if (isset($genre['name'])) {
                Genre::updateOrCreate(['id' => $genre['id']], $genre)->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        $people_ids = [];
        $credits = [];

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
                'order'         => null,
            ];
            $people_ids[] = $person['id'];
        }

        $people = [];

        foreach (array_unique($people_ids) as $person_id) {
            $client = new Client\Person($person_id);
            $person = $client->getData();
            $people[] = $tmdb->person_array($person);
        }

        Person::upsert($people, 'id');
        Tv::find($this->tv['id'])->people()->sync($credits);

        foreach ($this->tv['seasons'] as $season) {
            $client = new Client\Season($this->id, sprintf('%02d', $season['season_number']));
            $season = $client->getData();

            if (isset($season['season_number'])) {
                $seasonArray = [
                    'air_date'      => $tmdb->ifExists('air_date', $season),
                    'poster'        => $tmdb->image('poster', $season),
                    'name'          => $tmdb->ifExists('name', $season),
                    'overview'      => $tmdb->ifExists('overview', $season),
                    'season_number' => sprintf('%02d', $season['season_number']),
                    'tv_id'         => $this->id,
                ];

                Season::updateOrCreate(['id' => $season['id']], $seasonArray)->tv();

                foreach ($season['episodes'] as $episode) {
                    $client = new Client\Episode($this->id, sprintf('%02d', $season['season_number']), $episode['episode_number']);
                    $episode = $client->getData();

                    if (isset($episode['episode_number'])) {
                        $episodeArray = [
                            'tv_id'           => $this->id,
                            'air_date'        => $tmdb->ifExists('air_date', $episode),
                            'name'            => Str::limit($tmdb->ifExists('name', $episode), 200),
                            'episode_number'  => sprintf('%02d', $episode['episode_number']),
                            'overview'        => $tmdb->ifExists('overview', $episode),
                            'still'           => $tmdb->image('still', $episode),
                            'production_code' => $episode['production_code'],
                            'season_number'   => sprintf('%02d', $episode['season_number']),
                            'vote_average'    => $episode['vote_average'],
                            'vote_count'      => $episode['vote_count'],
                            'season_id'       => $season['id'],
                        ];

                        Episode::updateOrCreate(['id' => $episode['id']], $episodeArray)->season();
                    }
                }
            }
        }

        if (isset($this->tv['recommendations'])) {
            foreach ($this->tv['recommendations']['results'] as $recommendation) {
                if (Tv::where('id', '=', $recommendation['id'])->exists()) {
                    Recommendation::updateOrCreate(
                        ['recommendation_tv_id' => $recommendation['id'], 'tv_id' => $this->tv['id']],
                        ['title' => $recommendation['name'], 'vote_average' => $recommendation['vote_average'], 'poster' => $tmdb->image('poster', $recommendation), 'first_air_date' => $recommendation['first_air_date']]
                    );
                }
            }
        }
    }
}
