<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Services;

/**
 * Various tools for fanart api
 *
 */
class FanArt
{

    /**
     * Obtains backgroundimage
     *
     * @access public
     * @param $category Category ID
     * @param $imdb IMDB
     * @return string URL link to backgroundimage
     *
     */
    public static function getBackgroundImage($category, $imdb, $tvdb)
    {
        $default_image = "/img/default_backdrop.png";
        $curl = curl_init();

        if ($category == 1) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/movies/tt' . $imdb);
        } elseif ($category == 2) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/tv/' . $tvdb);
        } elseif ($category == 3) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/movies/tt' . $imdb);
        } else {
            return $default_image;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['api-key: 05e03e4887f762022f945ee1d27ca627']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($curl);
        $error_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error_code == 200 || !empty($results)) {
            $jsondata = json_decode($results, true);

            if ($category == 1) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['moviebackground'])) {
                    if (count($jsondata['moviebackground']) > 0) {
                        return $jsondata['moviebackground'][0]['url'];
                    }
                }
            } elseif ($category == 2) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['showbackground'])) {
                    if (count($jsondata['showbackground']) > 0) {
                        return $jsondata['showbackground'][0]['url'];
                    }
                }
            } elseif ($category == 3) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['moviebackground'])) {
                    if (count($jsondata['moviebackground']) > 0) {
                        return $jsondata['moviebackground'][0]['url'];
                    }
                }
            } else {
                return $default_image;
            }
        } else {
            return $default_image;
        }

        return $default_image;
    }


    /**
     * Obtains poster
     *
     * @access public
     * @param $category Category ID
     * @param $imdb IMDB
     * @return string URL link to poster
     *
     */
    public static function getPoster($category, $imdb, $tvdb)
    {
        $default_image = "/img/default_poster.png";
        $curl = curl_init();

        if ($category == 1) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/movies/tt' . $imdb);
        } elseif ($category == 2) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/tv/' . $tvdb);
        } elseif ($category == 3) {
            curl_setopt($curl, CURLOPT_URL, 'https://webservice.fanart.tv/v3/movies/tt' . $imdb);
        } else {
            return $default_image;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['api-key: 05e03e4887f762022f945ee1d27ca627']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($curl);
        $error_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error_code == 200 || !empty($results)) {
            $jsondata = json_decode($results, true);

            if ($category == 1) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['movieposter'])) {
                    if (count($jsondata['movieposter']) > 0) {
                        return $jsondata['movieposter'][0]['url'];
                    }
                }
            } elseif ($category == 2) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['tvposter'])) {
                    if (count($jsondata['tvposter']) > 0) {
                        return $jsondata['tvposter'][0]['url'];
                    }
                }
            } elseif ($category == 3) {
                /*
                * An arguement can be made that array_key_exists() should be used instead
                */
                if (isset($jsondata['movieposter'])) {
                    if (count($jsondata['movieposter']) > 0) {
                        return $jsondata['movieposter'][0]['url'];
                    }
                }
            } else {
                return $default_image;
            }
        } else {
            return $default_image;
        }

        return $default_image;
    }
}
