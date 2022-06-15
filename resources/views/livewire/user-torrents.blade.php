<div>
    <div class="container well search mt-5">
        <div class="form-horizontal form-condensed form-torrent-search form-bordered">
            <div class="mx-0 mt-5 form-group fatten-me">
                <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.name') }}</label>
                <div class="col-sm-9 fatten-me">
                    <input type="text" class="form-control" id="name" wire:model="name" placeholder="{{ __('torrent.name') }}">
                </div>
            </div>
            <div class="mx-0 mt-5 form-group fatten-me">
                <div class="mt-5 col-sm-1 label label-default fatten-me">
                    {{ __('torrent.filters') }}
                </div>
                <div class="col-sm-10">
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('unsatisfied'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.unsatisfieds') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('active'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('common.active') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('completed'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.completed') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('prewarn'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.prewarn') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('hitrun'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.hitrun') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('immune'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.immune') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('uploaded'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.uploaded') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('downloaded'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-torrents__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.downloaded') }}
                        </label>
                    </span>
                </div>
            </div>
            <div class="mx-0 mt-5 form-group fatten-me">
                <div class="mt-5 col-sm-1 label label-default fatten-me">
                    {{ __('torrent.moderation') }}
                </div>
                <div class="col-sm-10">
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-torrents__checkbox" wire:model="status" value="0">
                            {{ __('torrent.pending') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-torrents__checkbox" wire:model="status" value="1">
                            {{ __('torrent.approved') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-torrents__checkbox" wire:model="status" value="2">
                            {{ __('torrent.rejected') }}
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-torrents__checkbox" wire:model="status" value="3">
                            Postponed
                        </label>
                    </span>
                </div>
            </div>
            <div class="mx-0 mt-5 form-group fatten-me">
                <div class="mt-5 col-sm-1 label label-default fatten-me">Options</div>
                <div class="col-sm-10">
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-torrents__checkbox" wire:model="showMorePrecision">
                            Show more precision
                        </label>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <th class="user-torrents__name-header" wire:click="sortBy('name')" role="columnheader button">
                    {{ __('torrent.name') }}
                    @include('livewire.includes._sort-icon', ['field' => 'name'])
                </th>
                <th class="user-torrents__seeders-header" wire:click="sortBy('seeders')" role="columnheader button" title="{{ __('torrent.seeders') }}">
                    <i class="fas fa-arrow-alt-circle-up"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                </th>
                <th class="user-torrents__leechers-header" wire:click="sortBy('leechers')" role="columnheader button" title="{{ __('torrent.leechers') }}">
                    <i class="fas fa-arrow-alt-circle-down"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                </th>
                <th class="user-torrents__times-header" wire:click="sortBy('times_completed')" role="columnheader button" title="{{ __('torrent.completed') }}">
                    <i class="fas fa-check-circle"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                </th>
                <th class="user-torrents__agent-header" wire:click="sortBy('agent')" role="columnheader button">
                    {{ __('torrent.client') }}
                    @include('livewire.includes._sort-icon', ['field' => 'agent'])
                </th>
                <th class="user-torrents__size-header" wire:click="sortBy('size')" role="columnheader button">
                    {{ __('torrent.size') }}
                    @include('livewire.includes._sort-icon', ['field' => 'size'])
                </th>
                <th class="user-torrents__upload-header" wire:click="sortBy('actual_uploaded')" role="columnheader button">
                    {{ __('common.upload') }}
                    @include('livewire.includes._sort-icon', ['field' => 'actual_uploaded'])
                </th>
                <th class="user-torrents__download-header" wire:click="sortBy('actual_downloaded')" role="columnheader button">
                    {{ __('common.download') }}
                    @include('livewire.includes._sort-icon', ['field' => 'actual_downloaded'])
                </th>
                <th class="user-torrents__ratio-header" wire:click="sortBy('actual_ratio')" role="columnheader button">
                    {{ __('common.ratio') }}
                    @include('livewire.includes._sort-icon', ['field' => 'actual_ratio'])
                </th>
                <th class="user-torrents__leechtime-header" wire:click="sortBy('leechtime')" role="columnheader button">
                    Leeched
                    @include('livewire.includes._sort-icon', ['field' => 'leechtime'])
                </th>
                <th class="user-torrents__seedtime-header" wire:click="sortBy('seedtime')" role="columnheader button">
                    Seeded
                    @include('livewire.includes._sort-icon', ['field' => 'seedtime'])
                </th>
                <th class="user-torrents__created-at-header" wire:click="sortBy('created_at')" role="columnheader button">
                    {{ __('torrent.started') }}
                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                </th>
                <th class="user-torrents__updated-at-header" wire:click="sortBy('updated_at')" role="columnheader button">
                    {{ __('torrent.updated') }}
                    @include('livewire.includes._sort-icon', ['field' => 'updated_at'])
                </th>
                <th class="user-torrents__completed-at-header" wire:click="sortBy('completed_at')" role="columnheader button">
                    {{ __('torrent.completed') }}
                    @include('livewire.includes._sort-icon', ['field' => 'completed_at'])
                </th>
                <th class="user-torrents__seeding-header" wire:click="sortBy('seeding')" role="columnheader button" title="{{ __('torrent.seeding') }}">
                    <i class="fas fa-arrow-up"></i>
                </th>
                <th class="user-torrents__leeching-header" wire:click="sortBy('leeching')" role="columnheader button" title="{{ __('torrent.leeching') }}">
                    <i class="fas fa-arrow-down"></i>
                </th>
                <th class="user-torrents__prewarned-header" wire:click="sortBy('prewarn')" role="columnheader button" title="{{ __('torrent.prewarn') }}">
                    <i class="fas fa-exclamation"></i>
                </th>
                <th class="user-torrents__warned-header" wire:click="sortBy('hitrun')" role="columnheader button" title="{{ __('torrent.hitrun') }}">
                    <i class="fas fa-exclamation-triangle"></i>
                </th>
                <th class="user-torrents__immune-header" wire:click="sortBy('immune')" role="columnheader button" title="{{ __('torrent.immune') }}">
                    <i class="fas fa-syringe"></i>
                </th>
                <th class="user-torrents__upload-header" wire:click="sortBy('uploaded')" role="columnheader button" title="{{ __('torrent.uploaded') }}">
                    <i class="fas fa-upload"></i>
                </th>
                <th class="user-torrents__status-header" wire:click="sortBy('status')" role="columnheader button" title="{{ __('torrent.approved') }}">
                    <i class="fas fa-tasks"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'status'])
                </th>
                </thead>
                <tbody>
                @foreach ($histories as $history)
                    <tr>
                        <td>
                            <a class="user-torrents__name" href="{{ route('torrent', ['id' => $history->torrent_id]) }}">
                                {{ $history->name }}
                            </a>
                        </td>
                        <td class="user-torrents__seeders">
                            <a href="{{ route('peers', ['id' => $history->torrent_id]) }}">
                                <span class='text-green'>
                                    {{ $history->seeders }}
                                </span>
                            </a>
                        </td>
                        <td class="user-torrents__leechers">
                            <a href="{{ route('peers', ['id' => $history->torrent_id]) }}">
                                <span class='text-red'>
                                    {{ $history->leechers }}
                                </span>
                            </a>
                        </td>
                        <td class="user-torrents__times">
                            <a href="{{ route('history', ['id' => $history->torrent_id]) }}">
                                <span class='text-orange'>
                                    {{ $history->times_completed }}
                                </span>
                            </a>
                        </td>
                        <td class="user-torrents__agent text-purple">
                            {{ $history->agent ?: __('common.unknown') }}
                        </td>
                        <td class="user-torrents__size">
                            {{ App\Helpers\StringHelper::formatBytes($history->size) }}
                        </td>
                        <td class="user-torrents__upload" title="{{ __('user.actual-upload') }}">
                            <span class="text-green">
                                {{ App\Helpers\StringHelper::formatBytes($history->actual_uploaded, 2) }}
                            </span>
                            <br>
                            <span class="text-blue" title="{{ __('user.credited-upload') }}">
                                {{ App\Helpers\StringHelper::formatBytes($history->uploaded, 2) }}
                            </span>
                        </td>
                        <td class="user-torrents__download">
                            <span class="text-red" title="{{ __('user.actual-download') }}">
                                {{ App\Helpers\StringHelper::formatBytes($history->actual_downloaded, 2) }}
                            </span>
                            <br>
                            <span class="text-orange" title="{{ __('user.credited-download') }}">
                                {{ App\Helpers\StringHelper::formatBytes($history->downloaded, 2) }}
                            </span>
                        </td>
                        <td class="user-torrents__ratio">
                            @php($ratio = $history->actual_ratio < 1000 ? \number_format($history->actual_ratio, 2) : INF)
                            <span
                                @if ($ratio < 1)
                                    class="ratio-0{{ \floor($ratio * 10) }}"
                                @elseif ($ratio < 2)
                                    class="ratio-10"
                                @elseif ($ratio < 5)
                                    class="ratio-20"
                                @elseif ($ratio <= INF)
                                    class="ratio-50"
                                @endif
                                title="Actual ratio: {{ $history->actual_ratio }}"
                            >
                                {{ $ratio }}
                            </span>
                            <br>
                            @php($ratio = $history->ratio < 1000 ? \number_format($history->ratio, 2) : INF)
                            <span
                                @if ($ratio < 1)
                                    class="ratio-0{{ \floor($ratio * 10) }}"
                                @elseif ($ratio < 2)
                                    class="ratio-10"
                                @elseif ($ratio < 5)
                                    class="ratio-20"
                                @elseif ($ratio <= INF)
                                    class="ratio-50"
                                @endif
                                title="Credited ratio: {{ $history->ratio }}"
                            >
                                {{ $ratio }}
                            </span>
                        </td>
                        @if ($showMorePrecision)
                            <td class="user-torrents__leechtime">
                                @if ($history->leechtime === null)
                                    N/A
                                @else
                                    {{ App\Helpers\StringHelper::timeElapsed($history->leechtime) }}
                                @endif
                            </td>
                            <td class="user-torrents__seedtime">
                                <span class="{{ ($history->seedtime ?? 0) < config('hitrun.seedtime') ? 'text-red' : 'text-green' }}">
                                    @if ($history->seedtime === null)
                                        N/A
                                    @else
                                        {{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
                                    @endif
                                </span>
                            </td>
                            <td class="user-torrents__created-at">
                                <time datetime="{{ $history->created_at ?? 0}}">
                                    {{ $history->created_at ?? 'N/A' }}
                                </time>
                            </td>
                            <td class="user-torrents__updated-at">
                                <time datetime="{{ $history->updated_at ?? 0}}">
                                    {{ $history->updated_at ?? 'N/A' }}
                                </time>
                            </td>
                            <td class="user-torrents__completed-at">
                                <time datetime="{{ $history->completed_at ?? 0}}">
                                    {{ $history->completed_at ?? 'N/A' }}
                                </time>
                            </td>
                        @else
                            <td class="user-torrents__leechtime">
                                @if ($history->leechtime === null)
                                    N/A
                                @else
                                    {{ \implode(" ", \array_slice(\explode(" ", App\Helpers\StringHelper::timeElapsed($history->leechtime)), 0, 2)) }}
                                @endif
                            </td>
                            <td class="user-torrents__seedtime">
                                @if ($history->seedtime === null)
                                    N/A
                                @else
                                    <span class="{{ $history->seedtime < config('hitrun.seedtime') ? 'text-red' : 'text-green' }}">
                                        {{ \implode(" ", \array_slice(\explode(" ", App\Helpers\StringHelper::timeElapsed($history->seedtime)), 0, 2)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="user-torrents__created-at">
                                {{ $history->created_at === null ? 'N/A' : \explode(" ", $history->created_at)[0] }}
                            </td>
                            <td class="user-torrents__updated-at">
                                {{ $history->updated_at === null ? 'N/A' : \explode(" ", $history->updated_at)[0] }}
                            </td>
                            <td class="user-torrents__completed-at">
                                {{ $history->completed_at === null ? 'N/A' : \explode(" ", $history->completed_at)[0] }}
                            </td>
                        @endif
                        <td class="user-torrents__seeding">
                            @if ($history->seeding == 1)
                                <i class="{{ config('other.font-awesome') }} text-green fa-check" title="{{ __('torrent.seeding') }}"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not {{ __('torrent.seeding') }}"></i>
                            @endif
                        </td>
                        <td class="user-torrents__leeching">
                            @if ($history->leeching == 1)
                                <i class="{{ config('other.font-awesome') }} text-green fa-check" title="{{ __('torrent.leeching') }}"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not {{ __('torrent.leeching') }}"></i>
                            @endif
                        </td>
                        <td class="user-torrents__prewarned">
                            @if ($history->prewarn == 1)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green" title="Prewarned"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red" title="Not prewarned"></i>
                            @endif
                        </td>
                        <td class="user-torrents__warned">
                            @if ($history->hitrun == 1)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green" title="Warned"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red" title="Not warned"></i>
                            @endif
                        </td>
                        <td class="user-torrents__immune">
                            @if ($history->immune == 1)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green" title="Immune"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red" title="Not immune"></i>
                            @endif
                        </td>
                        <td class="user-torrents__uploader">
                            @if ($history->uploader == 1)
                                <i class="{{ config('other.font-awesome') }} text-green fa-check" title="{{ __('torrent.uploaded') }}"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not {{ __('torrent.uploaded') }}"></i>
                            @endif
                        </td>
                        <td class="user-torrents__status">
                            @switch($history->status)
                                @case(0)
                                    <span title="{{ __('torrent.pending') }}" class="{{ config('other.font-awesome') }} fa-tasks text-orange"></span>
                                    @break
                                @case(1)
                                    <span title="{{ __('torrent.approved') }}" class="{{ config('other.font-awesome') }} fa-check text-green"></span>
                                    @break
                                @case(2)
                                    <span title="{{ __('torrent.rejected') }}" class ="{{ config('other.font-awesome') }} fa-times text-red"></span>
                                    @break
                                @case(3)
                                    <span title="Postponed" class ="{{ config('other.font-awesome') }} fa-hourglass text-red"></span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        function ternaryCheckbox() {
            return {
                updateTernaryCheckboxProperties(el, state) {
                    el.indeterminate = (state === 'exclude');
                    el.checked = (state === 'include');
                },
                getNextTernaryCheckboxState(state) {
                    return (state === 'include') ? 'exclude' : (state === 'exclude' ? 'any' : 'include');
                }
            }
        }
    </script>
</div>