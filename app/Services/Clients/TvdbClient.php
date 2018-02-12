<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */
 
namespace App\Services\Clients;

use App\Services\Contracts\MovieTvInterface;
use App\Services\Data\Episode;
use App\Services\Data\Tv;
use Moinax\TvDb\Client as MoinaxTvDbClient;

class TvdbClient extends Client implements MovieTvInterface
{

    protected $apiUrl = 'thetvdb.com';
    protected $apiSecure = false;

    /**
     * @var MoinaxTvDbClient
     */
    private $tvdb_api;

    private $imagePath = 'https://thetvdb.com/banners/';

    public function __construct($apiKey)
    {
        parent::__construct($this->apiUrl, $apiKey);
        $this->tvdb_api = new MoinaxTvDbClient($this->apiUrl, $this->apiKey);
    }

    public function find($keys, $type = 'tv')
    {
        $this->validateKeys($keys);

        $key = 'tvdb' . $keys['imdb'];
        $result = $this->cache($key);
        if (!$result) {
            $result = $this->tvdb_api->getSerieByRemoteId(['imdbid' => $keys['imdb']]);
            $this->cache($key, $result);
        }

        if (!empty($result->id)) {
            return $this->tv($result->id);
        }

        return new Tv();
    }

    public function movie($id)
    {
        //
    }

    public function tv($id)
    {
        $this->validateKeys(['tvdb' => $id]);

        $key = 'tvdb' . $id;
        $result = $this->cache($key);
        if (!$result) {
            $result = $this->tvdb_api->getSerieEpisodes($id);
            $this->cache($key, $result);
        }

        return $this->formatTv($result);
    }

    public function person($id)
    {
    }

    private function formatTv($data)
    {
        $tv = $data['serie'];

        return new Tv([
            'imdb' => $tv->imdbId,
            'tvdb' => $tv->id,
            'title' => $tv->name,
            'releaseDate' => $tv->firstAired,
            'plot' => $tv->overview,
            'genres' => $tv->genres,
            'network' => $tv->network,
            'runtime' => $tv->runtime,
            'tvdbRating' => $tv->rating,
            'tvdbVotes' => $tv->ratingCount,
            'poster' => !empty($tv->poster) ? $this->imagePath . $tv->poster : null,
            'episodes' => $this->formatEpisodes($data['episodes'])
        ]);
    }

    private function formatEpisodes($episodes)
    {
        $tv_episodes = [];
        if (!empty($episodes)) {
            foreach ($episodes as $episode) {
                $tv_episodes[] = new Episode([
                    'episode' => $episode->number,
                    'season' => $episode->season,
                    'title' => $episode->name,
                    'releaseDate' => $episode->firstAired,
                    'plot' => $episode->overview,
                    'photo' => !empty($episode->thumbnail) ? $this->imagePath . $episode->thumbnail : null,
                ]);
            }
        }

        return $tv_episodes;
    }
}
