<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Services\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class IgdbClient
{
    /**
     * @var string
     */
    protected $igdbKey;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var integer
     */
    protected $cache;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    const VALID_RESOURCES = [
        'games' => 'games',
        'characters' => 'characters',
        'companies' => 'companies',
        'game_engines' => 'game_engines',
        'game_modes' => 'game_modes',
        'keywords' => 'keywords',
        'people' => 'people',
        'platforms' => 'platforms',
        'pulses' => 'pulses',
        'themes' => 'themes',
        'collections' => 'collections',
        'player_perspectives' => 'player_perspectives',
        'reviews' => 'reviews',
        'franchises' => 'franchises',
        'genres' => 'genres',
        'release_dates' => 'release_dates',
        'pulse_sources' => 'pulse_sources',
        'pages' => 'pages',
    ];


    /**
     * IgdbClient constructor.
     *
     * @param $key
     * @param $url
     * @throws \Exception
     */
    public function __construct($key, $url, $cache)
    {
        if (!is_string($key) || empty($key)) {
            throw new \Exception('IGDB API key is required, please visit https://api.igdb.com/ to request a key');
        }

        if (!is_string($url) || empty($url)) {
            throw new \Exception('IGDB Request URL is required, please visit https://api.igdb.com/ to get your Request URL');
        }

        $this->igdbKey = $key;
        $this->baseUrl = $url;
        $this->cache = $cache;
        $this->httpClient = new Client();
    }

    /**
     * Get character information.
     *
     * @param integer $characterId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getCharacter($characterId, $fields = ['*'])
    {

        $apiUrl = $this->getEndpoint('characters');
        $apiUrl .= $characterId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });


    }

    /**
     * Search characters by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchCharacters($search, $fields = ['*'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('characters');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });

    }

    /**
     * Search characters by name.
     *
     * @param array $filters
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function getReleases($filters = [], $fields = ['*'], $limit = 10, $offset = 0, $order = null, $expand = [])
    {
        $apiUrl = $this->getEndpoint('release_dates');

        $params = array(
            'fields' => implode(',', $fields),
            'filters' => $filters,
            'limit' => $limit,
            'offset' => $offset,
            'order' => $order,
            'expand' => implode(',', $expand)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });

    }

    /**
     * Get company information by ID.
     *
     * @param integer $companyId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getCompany($companyId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('companies');
        $apiUrl .= $companyId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search companies by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchCompanies($search, $fields = ['*'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('companies');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get franchise information.
     *
     * @param integer $franchiseId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getFranchise($franchiseId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('franchises');
        $apiUrl .= $franchiseId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search franchises by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchFranchises($search, $fields = ['*'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('franchises');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get game mode information by ID.
     *
     * @param integer $gameModeId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getGameMode($gameModeId, $fields = ['name', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('game_modes');
        $apiUrl .= $gameModeId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search game modes by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchGameModes($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('game_modes');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get game information by ID.
     *
     * @param integer $gameId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getGame($gameId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('games');
        $apiUrl .= $gameId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search games by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @param array $filters
     * @return \StdClass
     * @throws \Exception
     */
    public function searchGames($search, $fields = ['*'], $limit = 10, $offset = 0, $order = null, $filters = [])
    {
        $apiUrl = $this->getEndpoint('games');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'filters' => $filters,
            'order' => $order,
            'search' => $search,
        );
        if ($search == '') unset($params['search']);

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get genre information by ID.
     *
     * @param integer $genreId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getGenre($genreId, $fields = ['name', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('genres');
        $apiUrl .= $genreId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search genres by name.
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchGenres($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('genres');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get keyword information by ID.
     *
     * @param integer $keywordId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getKeyword($keywordId, $fields = ['name', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('keywords');
        $apiUrl .= $keywordId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search keywords by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchKeywords($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('keywords');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get people information by ID
     *
     * @param integer $personId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPerson($personId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('people');
        $apiUrl .= $personId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search people by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchPeople($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('people');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get platform information by ID
     *
     * @param integer $platformId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPlatform($platformId, $fields = ['name', 'logo', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('platforms');
        $apiUrl .= $platformId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search platforms by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchPlatforms($search, $fields = ['name', 'logo', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('platforms');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get player perspective information by ID
     *
     * @param integer $perspectiveId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPlayerPerspective($perspectiveId, $fields = ['name', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('player_perspectives');
        $apiUrl .= $perspectiveId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search player perspective by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchPlayerPerspectives($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('player_perspectives');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }


    /**
     * Get page information by ID
     *
     * @param integer $pageId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPage($pageId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('pages');
        $apiUrl .= $pageId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }



    /**
     * Get pulse information by ID
     *
     * @param integer $pulseId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPulse($pulseId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('pulses');
        $apiUrl .= $pulseId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Get pulse information by ID
     *
     * @param integer $pulseId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getPulseSource($sourceId)
    {
        $apiUrl = $this->getEndpoint('pulse_sources');
        $apiUrl .= $sourceId;

        $params = array(

        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search pulses by title
     *
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @return \StdClass
     * @throws \Exception
     */
    public function fetchPulses($fields = ['*'], $limit = 10, $offset = 0, $order = null, $filters = [])
    {
        $apiUrl = $this->getEndpoint('pulses');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'filters' => $filters,
            'order' => $order,
            'offset' => $offset
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get collection information by ID
     *
     * @param integer $collectionId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getCollection($collectionId, $fields = ['*'])
    {
        $apiUrl = $this->getEndpoint('collections');
        $apiUrl .= $collectionId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search collections by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchCollections($search, $fields = ['*'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('collections');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }

    /**
     * Get themes information by ID
     *
     * @param integer $themeId
     * @param array $fields
     * @return \StdClass
     * @throws \Exception
     */
    public function getTheme($themeId, $fields = ['name', 'slug', 'url'])
    {
        $apiUrl = $this->getEndpoint('themes');
        $apiUrl .= $themeId;

        $params = array(
            'fields' => implode(',', $fields)
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeSingle($apiData);
        });
    }

    /**
     * Search themes by name
     *
     * @param string $search
     * @param array $fields
     * @param integer $limit
     * @param integer $offset
     * @param string $order
     * @return \StdClass
     * @throws \Exception
     */
    public function searchThemes($search, $fields = ['name', 'slug', 'url'], $limit = 10, $offset = 0, $order = null)
    {
        $apiUrl = $this->getEndpoint('themes');

        $params = array(
            'fields' => implode(',', $fields),
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
        );

        return Cache::remember(md5($apiUrl . json_encode($params)), $this->cache, function () use ($apiUrl, $params)
        {
            $apiData = $this->apiGet($apiUrl, $params);
            return $this->decodeMultiple($apiData);
        });
    }


    /*
     *  Internally used Methods, set visibility to public to enable more flexibility
     */
    /**
     * @param $name
     * @return mixed
     */
    private function getEndpoint($name)
    {
        return rtrim($this->baseUrl, '/').'/'.self::VALID_RESOURCES[$name].'/';
    }

    /**
     * Decode the response from IGDB, extract the single resource object.
     * (Don't use this to decode the response containing list of objects)
     *
     * @param  string $apiData the api response from IGDB
     * @throws \Exception
     * @return \StdClass  an IGDB resource object
     */
    private function decodeSingle(&$apiData)
    {
        $resObj = json_decode($apiData);

        if (isset($resObj->status)) {
            $msg = "Error " . $resObj->status . " " . $resObj->message;
            throw new \Exception($msg);
        }

        if (!is_array($resObj) || count($resObj) == 0) {
            return false;
        }

        return $resObj[0];
    }

    /**
     * Decode the response from IGDB, extract the multiple resource object.
     *
     * @param  string $apiData the api response from IGDB
     * @throws \Exception
     * @return \StdClass  an IGDB resource object
     */
    private function decodeMultiple(&$apiData)
    {
        $resObj = json_decode($apiData);

        if (isset($resObj->status)) {
            $msg = "Error " . $resObj->status . " " . $resObj->message;
            throw new \Exception($msg);
        } else {
            //$itemsArray = $resObj->items;
            if (!is_array($resObj)) {
                return false;
            } else {
                return $resObj;
            }
        }
    }

    /**
     * Using CURL to issue a GET request
     *
     * @param $url
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function apiGet($url, $params)
    {
        if (isset($params['filters'])) {
            foreach ($params['filters'] as $filter) {
                list($key, $value) = explode('=', $filter, 2);
                $params[$key] = $value;
            }
            unset($params['filters']);
        }

        $url = $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($params);

        try {
            $response = $this->httpClient->request('GET', $url, [
                'headers' => [
                    'user-key' => $this->igdbKey,
                    'Accept' => 'application/json'
                ]
            ]);
        } catch (RequestException $exception) {
            if ($response = $exception->getResponse()) {
                throw new \Exception($exception);
            }
            throw new \Exception($exception);
        } catch (Exception $exception) {
            throw new \Exception($exception);
        }

        return $response->getBody();
    }

}