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

namespace App\Repositories;

use App\Torrent;
use App\Category;
use App\Type;

class TorrentFacetedRepository
{
    /**
     * Return a collection of Category Name from storage
     * @return \Illuminate\Support\Collection
     */
    public function categories()
    {
        return Category::all()->sortBy('position')->pluck('name', 'id');
    }

    /**
     * Return a collection of Type Name from storage
     * @return \Illuminate\Support\Collection
     */
    public function types()
    {
        return Type::all()->where('slug', '!=', 'sd')->sortBy('position')->pluck('name', 'id');
    }

    /**
     * Options for sort the search result
     * @return array
     */
    public function sorting()
    {
        return [
            'created_at' => 'Date',
            'name' => 'Name',
            'seeders' => 'Seeders',
            'leechers' => 'Leechers',
            'times_completed' => 'Times Completed',
            'size' => 'Size',
        ];
    }

    /**
     * Options for sort the search result by direction
     * @return array
     */
    public function direction()
    {
        return [
            'asc' => 'Ascending',
            'desc' => 'Descending'
        ];
    }
}
