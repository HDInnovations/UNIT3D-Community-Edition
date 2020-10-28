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
use App\Models\Company;
use App\Models\Crew;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\GuestStar;
use App\Models\Network;
use App\Models\Person;
use App\Models\Season;
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

    public $tv;
    public $id;

    public function __construct($tv, $id)
    {
        $this->tv = $tv;
        $this->id = $id;
    }

    public function handle()
    {
        $helper = new TMDB();

        foreach ($this->tv['production_companies'] as $production_company) {
            if (isset($production_company['name'])) {
                $production_company_array = [
                    'description'    => $helper->ifExists('description', $production_company),
                    'name'           => $production_company['name'],
                    'headquarters'   => $helper->ifExists('headquarters', $production_company),
                    'homepage'       => $helper->ifExists('homepage', $production_company),
                    'logo'           => $helper->image('logo', $production_company),
                    'origin_country' => $helper->ifExists('origin_country', $production_company),
                ];
                Company::updateOrCreate(['id' => $production_company['id']], $production_company_array)->tv()->syncWithoutDetaching([$this->tv['id']]);
            }
        }

        foreach ($this->tv['created_by'] as $person) {
            if (isset($person['id'])) {
                Person::updateOrCreate(['id' => $person['id']], $helper->person_array($person))->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['networks'] as $network) {
            $client = new Client\Network($network['id']);
            $network = $client->index();
            if (isset($network['name'])) {
                if (isset($network['images']['logos'][0]) && array_key_exists('file_path', $network['images']['logos'][0])) {
                    $logo = 'https://image.tmdb.org/t/p/original'.$network['images']['logos'][0]['file_path'];
                } else {
                    $logo = null;
                }
                $network_array = [
                    'headquarters'   => $helper->ifExists('headquarters', $network),
                    'homepage'       => $helper->ifExists('homepage', $network),
                    'logo'           => $logo,
                    'name'           => $network['name'],
                    'origin_country' => $network['origin_country'],
                ];
                Network::updateOrCreate(['id' => $network['id']], $network_array)->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['genres'] as $genre) {
            if (isset($genre['name'])) {
                Genre::updateOrCreate(['id' => $genre['id']], $genre)->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['credits']['crew'] as $crew) {
            if (isset($crew['id'])) {
                Crew::updateOrCreate(['id' => $crew['id']], $helper->person_array($crew))->tv()->syncWithoutDetaching([$this->id]);
                Person::updateOrCreate(['id' => $crew['id']], $helper->person_array($crew))->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['credits']['cast'] as $cast) {
            if (isset($cast['id'])) {
                Cast::updateOrCreate(['id' => $cast['id']], $helper->cast_array($cast))->tv()->syncWithoutDetaching([$this->id]);
                Person::updateOrCreate(['id' => $cast['id']], $helper->person_array($cast))->tv()->syncWithoutDetaching([$this->id]);
            }
        }

        foreach ($this->tv['seasons'] as $season) {
            $client = new Client\Season($this->id, sprintf('%02d', $season['season_number']));
            $season = $client->index();
            if (isset($season['season_number'])) {
                $season_array = [
                    'air_date'      => $helper->ifExists('air_date', $season),
                    'poster'        => $helper->image('poster', $season),
                    'name'          => $helper->ifExists('name', $season),
                    'overview'      => $helper->ifExists('overview', $season),
                    'season_number' => sprintf('%02d', $season['season_number']),
                    'tv_id'         => $this->id,
                ];

                Season::updateOrCreate(['id' => $season['id']], $season_array)->tv();

                foreach ($season['episodes'] as $episode) {
                    $client = new Client\Episode($this->id, sprintf('%02d', $season['season_number']), $episode['episode_number']);
                    $episode = $client->index();
                    if (isset($episode['episode_number'])) {
                        $episode_array = [
                            'tv_id'           => $this->id,
                            'air_date'        => $helper->ifExists('air_date', $episode),
                            'name'            => Str::limit($helper->ifExists('name', $episode), 200),
                            'episode_number'  => sprintf('%02d', $episode['episode_number']),
                            'overview'        => $helper->ifExists('overview', $episode),
                            'still'           => $helper->image('still', $episode),
                            'production_code' => $episode['production_code'],
                            'season_number'   => sprintf('%02d', $episode['season_number']),
                            'vote_average'    => $episode['vote_average'],
                            'vote_count'      => $episode['vote_count'],
                            'season_id'       => $season['id'],
                        ];

                        Episode::updateOrCreate(['id' => $episode['id']], $episode_array)->season();

                        foreach ($episode['credits']['guest_stars'] as $person) {
                            if (isset($person['id'])) {
                                GuestStar::updateOrCreate(['id' => $person['id']], $helper->person_array($person))->episode()->syncWithoutDetaching([$episode['id']]);
                                Person::updateOrCreate(['id' => $person['id']], $helper->person_array($person))->tv()->syncWithoutDetaching([$this->id]);
                            }
                        }
                    }
                }

                foreach ($season['credits']['cast'] as $person) {
                    if (isset($person['id'])) {
                        Cast::updateOrCreate(['id' => $person['id']], $helper->cast_array($person))->season()->syncWithoutDetaching([$season['id']]);
                        Cast::updateOrCreate(['id' => $person['id']], $helper->cast_array($person))->tv()->syncWithoutDetaching([$this->id]);
                        Person::updateOrCreate(['id' => $person['id']], $helper->person_array($person))->tv()->syncWithoutDetaching([$this->id]);
                        $client = new Client\Person($person['id']);
                        $people = $client->index();
                        Crew::updateOrCreate(['id' => $people['id']], $helper->person_array($people))->season()->syncWithoutDetaching([$season['id']]);
                    }
                }

                foreach ($season['credits']['crew'] as $crew) {
                    if (isset($crew['id'])) {
                        Crew::updateOrCreate(['id' => $crew['id']], $helper->person_array($crew))->season()->syncWithoutDetaching([$season['id']]);
                        Person::updateOrCreate(['id' => $crew['id']], $helper->person_array($crew))->tv()->syncWithoutDetaching([$this->id]);
                    }
                }
            }
        }
    }
}
