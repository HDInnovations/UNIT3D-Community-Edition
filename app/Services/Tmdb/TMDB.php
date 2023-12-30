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
    /**
     * @param array<mixed> $array
     */
    public function image(string $type, array $array): ?string
    {
        if (isset($array[$type.'_path'])) {
            return 'https://image.tmdb.org/t/p/original'.$array[$type.'_path'];
        }

        return null;
    }

    /**
     * @param array<mixed> $array
     */
    public function trailer(array $array): ?string
    {
        if (isset($array['videos']['results'])) {
            return 'https://www.youtube.com/embed/'.$array['videos']['results'][0]['key'];
        }

        return null;
    }

    /**
     * @param array<mixed> $array
     */
    public function ifHasItems(string $type, array $array): mixed
    {
        return $array[$type][0] ?? null;
    }

    /**
     * @param array<mixed> $array
     */
    public function ifExists(string $type, array $array): mixed
    {
        if (isset($array[$type]) && !empty($array[$type])) {
            return $array[$type];
        }

        return null;
    }
}
