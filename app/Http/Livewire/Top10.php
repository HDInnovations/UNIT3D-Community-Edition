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

use App\Models\Torrent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Top10 extends Component
{
    final public function getTorrentsDayProperty()
    {
        $matches = Cache::remember('top10DayMatches', 3_600, fn () => DB::select('SELECT torrent_id, count(*) FROM history WHERE completed_at >= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY) GROUP BY torrent_id ORDER BY count(*) DESC LIMIT 10'));

        return Cache::remember('top10DayTorrents', 3_600, fn () => Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->whereIntegerInRaw('id', collect($matches)->pluck('torrent_id')->toArray())
            ->get());
    }

    final public function getTorrentsWeekProperty()
    {
        $matches = Cache::remember('top10WeekMatches', 3_600, fn () => DB::select('SELECT torrent_id, count(*) FROM history WHERE completed_at >= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 WEEK) GROUP BY torrent_id ORDER BY count(*) DESC LIMIT 10'));

        return Cache::remember('top10WeekTorrents', 3_600, fn () => Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->whereIntegerInRaw('id', collect($matches)->pluck('torrent_id')->toArray())
            ->get());
    }

    final public function getTorrentsMonthProperty()
    {
        $matches = Cache::remember('top10MonthMatches', 3_600, fn () => DB::select('SELECT torrent_id, count(*) FROM history WHERE completed_at >= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 MONTH) GROUP BY torrent_id ORDER BY count(*) DESC LIMIT 10'));

        return Cache::remember('top10MonthTorrents', 3_600, fn () => Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->whereIntegerInRaw('id', collect($matches)->pluck('torrent_id')->toArray())
            ->get());
    }

    final public function getTorrentsYearProperty()
    {
        $matches = Cache::remember('top10YearMatches', 3_600, fn () => DB::select('SELECT torrent_id, count(*) FROM history WHERE completed_at >= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 YEAR) GROUP BY torrent_id ORDER BY count(*) DESC LIMIT 10'));

        return Cache::remember('top10YearTorrents', 3_600, fn () => Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->whereIntegerInRaw('id', collect($matches)->pluck('torrent_id')->toArray())
            ->get());
    }

    final public function getTorrentsAllProperty()
    {
        $matches = Cache::remember('top10AllMatches', 3_600, fn () => DB::select('SELECT torrent_id, count(*) FROM history GROUP BY torrent_id ORDER BY count(*) DESC LIMIT 10'));

        return Cache::remember('top10AllTorrents', 3_600, fn () => Torrent::with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->whereIntegerInRaw('id', collect($matches)->pluck('torrent_id')->toArray())
            ->get());
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.top10', [
            'user'          => \auth()->user(),
            'torrentsDay'   => $this->torrentsDay,
            'torrentsWeek'  => $this->torrentsWeek,
            'torrentsMonth' => $this->torrentsMonth,
            'torrentsYear'  => $this->torrentsYear,
            'torrentsAll'   => $this->torrentsAll,
        ]);
    }
}
