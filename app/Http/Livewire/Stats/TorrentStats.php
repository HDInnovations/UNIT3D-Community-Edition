<?php

declare(strict_types=1);

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

namespace App\Http\Livewire\Stats;

use App\Models\Category;
use App\Models\Torrent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy(isolate: true)]
class TorrentStats extends Component
{
    #[Computed(cache: true, seconds: 10 * 60)]
    final public function totalCount(): int
    {
        return Torrent::query()->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function sdCount(): int
    {
        return Torrent::query()->where('sd', '=', 1)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    #[Computed(cache: true, seconds: 10 * 60)]
    final public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()->withCount('torrents')->orderBy('position')->get();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function sizeSum(): int
    {
        return (int) Torrent::query()->sum('size');
    }

    final public function placeholder(): string
    {
        return <<<'HTML'
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <div class="panel__body">Loading...</div>
        </section>
        HTML;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.stats.torrent-stats', [
            'num_torrent'  => $this->totalCount,
            'categories'   => $this->categories,
            'num_hd'       => $this->totalCount - $this->sdCount,
            'num_sd'       => $this->sdCount,
            'torrent_size' => $this->sizeSum,
        ]);
    }
}
