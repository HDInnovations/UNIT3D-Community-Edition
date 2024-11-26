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
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\History;
use App\Models\Torrent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

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
    #[Validate('in:day,week,weekly,month,year,all,custom')]
    public string $interval = 'day';

    #[Url(history: true)]
    #[Validate('sometimes|date_format:Y-m-d')]
    public string $from = '';

    #[Url(history: true)]
    #[Validate('sometimes|date_format:Y-m-d')]
    public string $until = '';

    public function updatingFrom(string &$value): void
    {
        try {
            $value = Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            $value = now()->subDay()->format('Y-m-d');
        }
    }

    public function updatingUntil(string &$value): void
    {
        try {
            $value = Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            $value = now()->format('Y-m-d');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Torrent>
     */
    #[Computed]
    final public function works(): Collection
    {
        $this->validate();

        return cache()->remember(
            'top10-'.$this->interval.'-'.($this->from ?? '').'-'.($this->until ?? '').'-'.$this->metaType,
            0, //3600,
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
                ->when($this->interval === 'custom', fn ($query) => $query->whereBetween('history.completed_at', [$this->from ?: now(), $this->until ?: now()]))
                ->whereRelation('category', $this->metaType, '=', true)
                // Small torrents screw the stats since users download them only to farm bon.
                ->where('torrents.size', '>', 1024 * 1024 * 1024)
                ->groupBy('tmdb')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(250)
                ->get('tmdb')
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int|string, \Illuminate\Database\Eloquent\Collection<int, Torrent>>
     * @phpstan-ignore generics.notSubtype (I can't figure out the correct return type to silence this error)
     */
    #[Computed]
    final public function weekly(): Collection
    {
        $this->validate();

        return cache()->remember(
            'weekly-charts:'.$this->metaType,
            24 * 3600,
            fn () => Torrent::query()
                ->withoutGlobalScopes()
                ->with($this->metaType === 'movie_meta' ? 'movie' : 'tv')
                ->fromSub(
                    History::query()
                        ->withoutGlobalScopes()
                        ->join('torrents', 'torrents.id', '=', 'history.torrent_id')
                        ->join('categories', fn (JoinClause $join) => $join->on('torrents.category_id', '=', 'categories.id')->where($this->metaType, '=', true))
                        ->select([
                            DB::raw('FROM_DAYS(TO_DAYS(history.created_at) - MOD(TO_DAYS(history.created_at) - 1, 7)) AS week_start'),
                            'tmdb',
                            DB::raw('MIN(categories.id) as category_id'),
                            DB::raw('COUNT(*) AS download_count'),
                            DB::raw('ROW_NUMBER() OVER (PARTITION BY FROM_DAYS(TO_DAYS(history.created_at) - MOD(TO_DAYS(history.created_at) - 1, 7)) ORDER BY COUNT(*) DESC) AS place'),
                        ])
                        ->where('tmdb', '!=', 0)
                        // Small torrents screw the stats since users download them only to farm bon.
                        ->where('torrents.size', '>', 1024 * 1024 * 1024)
                        ->groupBy('week_start', 'tmdb'),
                    'ranked_groups',
                )
                ->where('place', '<=', 10)
                ->orderByDesc('week_start')
                ->orderBy('place')
                ->withCasts([
                    'week_start' => 'datetime',
                ])
                ->get()
                ->groupBy('week_start')
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
            $metaTypes[(string) __('mediahub.movie')] = 'movie_meta';
        }

        if (Category::where('tv_meta', '=', true)->exists()) {
            $metaTypes[(string) __('mediahub.show')] = 'tv_meta';
        }

        return $metaTypes;
    }

    final public function placeholder(): string
    {
        return <<<'HTML'
        <section class="panelV2">
            <h2 class="panel__heading">Top Titles</h2>
            <div class="panel__body">Loading...</div>
        </section>
        HTML;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.top10', [
            'user'      => auth()->user(),
            'works'     => $this->interval === 'weekly' ? $this->weekly : $this->works,
            'metaTypes' => $this->metaTypes,
        ]);
    }
}
