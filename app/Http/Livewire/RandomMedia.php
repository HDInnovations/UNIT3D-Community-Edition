<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Tv;
use App\Models\Movie;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RandomMedia extends Component
{
    /**
     * @return \Illuminate\Support\Collection<int, Movie>
     */
    #[Computed]
    final public function movies(): \Illuminate\Support\Collection
    {
        $cacheKey = config('cache.prefix').':random-media-movie-ids';

        $movieIds = Redis::connection('cache')->command('SRANDMEMBER', [$cacheKey, 3]);

        return Movie::query()
            ->select(['id', 'backdrop', 'title', 'release_date'])
            ->whereIn('id', $movieIds)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Movie>
     */
    #[Computed]
    final public function movies2(): \Illuminate\Support\Collection
    {
        $cacheKey = config('cache.prefix').':random-media-movie-ids';

        $movieIds = Redis::connection('cache')->command('SRANDMEMBER', [$cacheKey, 3]);

        return Movie::query()
            ->select(['id', 'backdrop', 'title', 'release_date'])
            ->whereIn('id', $movieIds)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Tv>
     */
    #[Computed]
    final public function tvs(): \Illuminate\Support\Collection
    {
        $cacheKey = config('cache.prefix').':random-media-tv-ids';

        $tvIds = Redis::connection('cache')->command('SRANDMEMBER', [$cacheKey, 3]);

        return Tv::query()
            ->select(['id', 'backdrop', 'name', 'first_air_date'])
            ->whereIn('id', $tvIds)
            ->get();
    }

    final public function render(): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.random-media', [
            'movies'  => $this->movies,
            'movies2' => $this->movies2,
            'tvs'     => $this->tvs
        ]);
    }
}
