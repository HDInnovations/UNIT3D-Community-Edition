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

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Torrent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * @property \Illuminate\Database\Eloquent\Collection<int, Torrent> $works
 * @property array<string, string>                                  $metaTypes
 */
class Top10 extends Component
{
    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    #[Validate('in:movie_meta,tv_meta')]
    public string $metaType = 'movie_meta';

    #[Url(history: true)]
    #[Validate('in:day,week,month,year,all')]
    public string $interval = 'day';

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Torrent>
     */
    #[Computed]
    final public function works(): Collection
    {
        $this->validate();

        return cache()->remember(
            'top10-'.$this->interval.'-'.$this->metaType,
            3600,
            fn () => Torrent::query()
                ->when(
                    $this->metaType === 'tv_meta',
                    fn ($query) => $query->with('tv'),
                    fn ($query) => $query->with('movie'),
                )
                ->select([
                    'tmdb',
                    DB::raw('MIN(category_id) as category_id'),
                    DB::raw('COUNT(*) as download_count'),
                ])
                ->join('history', 'history.torrent_id', '=', 'torrents.id')
                ->where('tmdb', '!=', 0)
                ->when($this->interval === 'day', fn ($query) => $query->whereBetween('history.completed_at', [now()->subDay(), now()]))
                ->when($this->interval === 'week', fn ($query) => $query->whereBetween('history.completed_at', [now()->subWeek(), now()]))
                ->when($this->interval === 'month', fn ($query) => $query->whereBetween('history.completed_at', [now()->subMonth(), now()]))
                ->when($this->interval === 'year', fn ($query) => $query->whereBetween('history.completed_at', [now()->subYear(), now()]))
                ->when($this->interval === 'all', fn ($query) => $query->whereNotNull('history.completed_at'))
                ->whereIn('torrents.category_id', Category::select('id')->where($this->metaType, '=', true))
                // Small torrents screw the stats since users download them only to farm bon.
                ->where('torrents.size', '>', 1024 * 1024 * 1024)
                ->groupBy('tmdb')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(250)
                ->get('tmdb')
        );
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    final public function metaTypes(): array
    {
        $metaTypes = [];

        if (Category::where('movie_meta', '=', true)->exists()) {
            $metaTypes[__('mediahub.movie')] = 'movie_meta';
        }

        if (Category::where('tv_meta', '=', true)->exists()) {
            $metaTypes[__('mediahub.show')] = 'tv_meta';
        }

        return $metaTypes;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.top10', [
            'user'      => auth()->user(),
            'works'     => $this->works,
            'metaTypes' => $this->metaTypes,
        ]);
    }
}
