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

namespace App\Services\Tmdb;

class TMDB
{
    public function image($type, $array)
    {
        if (isset($array[$type . '_path'])) {
            return $type = "https://image.tmdb.org/t/p/original" . $array[$type . '_path'];
        } else {
            return $type = null;
        }
    }

    public function trailer($array)
    {
        if (isset($array["videos"]["results"])) {
            return "https://www.youtube.com/embed/" . $array["videos"]["results"][0]["key"];
        } else {
            return null;
        }
    }

    public function ifHasItems($type, $array)
    {
        if (isset($array[$type][0])) {
            return $array[$type][0];
        } else {
            return null;
        }
    }

    public function ifExists($type, $array)
    {
        if (isset($array[$type]) && !empty($array[$type])) {
            return $array[$type];
        } else {
            return null;
        }
    }

    public function cast_array($cast)
    {
        return [
            "character" => $cast['character'],
            "credit_id" => $cast['credit_id'],
            "gender" => $cast['gender'],
            "name" => $cast['name'],
            "order" => $cast['order'],
            'still' => $this->image('profile', $cast),
        ];
    }

    public function person_array($person)
    {
        return [
            "birthday" => $this->ifExists('birthday', $person),
            "known_for_department" => $this->ifExists('known_for_department', $person),
            "deathday" => $this->ifExists('deathday', $person),
            "name" => $this->ifExists('name', $person),
            //"also_known_as" => $person['also_known_as'] ?? null,
            "gender" => $this->ifExists('gender', $person),
            "biography" => $this->ifExists('biography', $person),
            "popularity" => $this->ifExists('popularity', $person),
            "place_of_birth" => $this->ifExists('place_of_birth', $person),
            'still' => $this->image('profile', $person),
            "adult" => $this->ifExists('adult', $person),
            "imdb_id" => $this->ifExists('imdb_id', $person),
            "homepage" => $this->ifExists('homepage', $person),
        ];
    }
}
