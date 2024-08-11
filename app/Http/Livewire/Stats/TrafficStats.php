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

use App\Models\History;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy(isolate: true)]
class TrafficStats extends Component
{
    #[Computed(cache: true, seconds: 10 * 60)]
    final public function actualUpload(): int
    {
        return (int) History::query()->sum('actual_uploaded');
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function creditedUpload(): int
    {
        return (int) History::query()->sum('uploaded');
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function actualDownload(): int
    {
        return (int) History::query()->sum('actual_downloaded');
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function creditedDownload(): int
    {
        return (int) History::query()->sum('downloaded');
    }

    final public function placeholder(): string
    {
        return <<<'HTML'
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('stat.total-traffic') }}</h2>
            <div class="panel__body">Loading...</div>
        </section>
        HTML;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.stats.traffic-stats', [
            'actual_upload'     => $this->actualUpload,
            'actual_download'   => $this->actualDownload,
            'actual_up_down'    => $this->actualUpload + $this->actualDownload,
            'credited_upload'   => $this->creditedUpload,
            'credited_download' => $this->creditedDownload,
            'credited_up_down'  => $this->creditedUpload + $this->creditedDownload,
        ]);
    }
}
