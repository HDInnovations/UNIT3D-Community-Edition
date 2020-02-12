<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Services\Clients;

use GuzzleHttp\Client as GuzzleClient;

abstract class Client
{
    protected $guzzle;

    protected $apiUrl;

    protected $apiKey;

    protected $apiSecure = false;

    public function __construct($apiUrl, $apiKey = null)
    {
        $this->apiUrl = ($this->apiSecure ? 'https://' : 'http://').$apiUrl;
        $this->apiKey = $apiKey;
        $this->guzzle = new GuzzleClient();
    }

    public function request($url, array $options = [])
    {
        $key = md5($url.serialize($options));
        if ($cache = $this->cache($key)) {
            return $cache;
        }

        try {
            $response = $this->guzzle->request('GET', $url, $options);
        } catch (\Exception $e) {
        }

        if (!empty($response)) {
            $this->validateStatus($response->getStatusCode());
            $content = $response->getBody()->getContents();

            return $this->cache($key, $content);
        }
    }

    public function toArray($string)
    {
        return json_decode($string, true);
    }

    public function toJson(array $array, $options = 0)
    {
        return json_encode($array, $options);
    }

    public function cache($key, $data = null)
    {
        $key = 'movietvdb:'.$key;

        if ($data) {
            cache()->remember($key, 7 * 24 * 60, function () use ($data) {
                return serialize($data);
            });
        }

        if (cache()->has($key)) {
            return unserialize(cache()->get($key));
        }

        return $data;
    }

    protected function validateKeys($keys)
    {
        /*if (!empty($keys['imdb'])) {
            if (!preg_match('/tt\\d{7}/', $keys['imdb'])) {
                throw new \InvalidArgumentException('Invalid IMDB ID');
            }
        }

        if (!empty($keys['tmdb'])) {
            if (!is_numeric($keys['tmdb'])) {
                throw new \InvalidArgumentException('Invalid TMDB ID');
            }
        }

        if (!empty($keys['tvdb'])) {
            if (!is_numeric($keys['tvdb'])) {
                throw new \InvalidArgumentException('Invalid TVDB ID');
            }
        }*/
    }

    protected function validateStatus($statusCode)
    {
        if ($statusCode < 200 && $statusCode > 299) {
            throw new \HttpResponseException('Invalid Status Code');
        }
    }
}
