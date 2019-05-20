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

namespace App\Traits;

use Carbon\Carbon;

trait UsersOnlineTrait
{
    public function allOnline()
    {
        return $this->all()->filter->isOnline();
    }

    public function isOnline()
    {
        return cache()->has($this->getCacheKey());
    }

    public function leastRecentOnline()
    {
        return $this->allOnline()
            ->sortBy(function ($user) {
                return $user->getCachedAt();
            });
    }

    public function mostRecentOnline()
    {
        return $this->allOnline()
            ->sortByDesc(function ($user) {
                return $user->getCachedAt();
            });
    }

    public function getCachedAt()
    {
        if (empty($cache = cache()->get($this->getCacheKey()))) {
            return 0;
        }

        return $cache['cachedAt'];
    }

    public function setCache($seconds = 300)
    {
        return cache()->put(
            $this->getCacheKey(),
            $this->getCacheContent(),
            $seconds
        );
    }

    public function getCacheContent()
    {
        if (! empty($cache = cache()->get($this->getCacheKey()))) {
            return $cache;
        }
        $cachedAt = Carbon::now();

        return [
            'cachedAt' => $cachedAt,
            'user' => $this,
        ];
    }

    public function pullCache()
    {
        cache()->pull($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return sprintf('%s-%s', 'UserOnline', $this->id);
    }
}
