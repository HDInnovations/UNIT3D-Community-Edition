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
                <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.filters') }}</label>
                <div class="col-sm-10">
                    <span class="badge-user">
                        <label style="user-select: none" class="inline" x-data="{ state: @entangle('seeding'), ...ternaryCheckbox() }">
                            <input
                                type="checkbox"
                                class="user-active__checkbox"
                                x-init="updateTernaryCheckboxProperties($el, state)"
                                x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state);"
                                x-bind:checked="state === 'include'"
                            >
                            {{ __('torrent.seeding') }}
                        </label>
                    </span>
                </div>
            </div>
            <div class="mx-0 mt-5 form-group fatten-me">
                <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">Precision</label>
                <div class="col-sm-10">
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" class="user-active__checkbox" wire:model="showMorePrecision">
                            Show more precision
                        </label>
                    </span>
                </div>
            </div>
            <div class="mx-0 mt-5 form-group fatten-me">
                <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.quantity') }}</label>
                <div class="col-sm-9">
                    <select wire:model="perPage" class="form-control">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <th class="user-active__name-header" wire:click="sortBy('name')" role="columnheader button">
                    {{ __('torrent.name') }}
                    @include('livewire.includes._sort-icon', ['field' => 'name'])
                </th>
                <th class="user-active__seeders-header" wire:click="sortBy('seeders')" role="columnheader button" title="{{ __('torrent.seeders') }}">
                    <i class="fas fa-arrow-alt-circle-up"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                </th>
                <th class="user-active__leechers-header" wire:click="sortBy('leechers')" role="columnheader button" title="{{ __('torrent.leechers') }}">
                    <i class="fas fa-arrow-alt-circle-down"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                </th>
                <th class="user-active__times-header" wire:click="sortBy('times_completed')" role="columnheader button" title="{{ __('torrent.completed') }}">
                    <i class="fas fa-check-circle"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                </th>
                <th class="user-active__agent-header" wire:click="sortBy('agent')" role="columnheader button">
                    {{ __('torrent.client') }}
                    @include('livewire.includes._sort-icon', ['field' => 'agent'])
                </th>
                <th class="user-active__ip-header" wire:click="sortBy('ip')" role="columnheader button">
                    {{ __('common.ip') }}
                    @include('livewire.includes._sort-icon', ['field' => 'ip'])
                </th>
                <th class="user-active__port-header" wire:click="sortBy('port')" role="columnheader button">
                    {{ __('common.port') }}
                    @include('livewire.includes._sort-icon', ['field' => 'port'])
                </th>
                @if (\config('announce.connectable_check'))
                    <th class="user-active__connectable-header">
                        <i class="{{ config('other.font-awesome') }} fa-wifi" title="Connectable"></i>
                    </th>
                @endif
                <th class="user-active__seeding-header" wire:click="sortBy('size')" role="columnheader button" title="{{ __('torrent.seeding') }}">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'seeding'])
                </th>
                <th class="user-active__size-header" wire:click="sortBy('size')" role="columnheader button">
                    {{ __('torrent.size') }}
                    @include('livewire.includes._sort-icon', ['field' => 'size'])
                </th>
                <th class="user-active__uploaded-header" wire:click="sortBy('uploaded')" role="columnheader button">
                    {{ __('torrent.uploaded') }}
                    @include('livewire.includes._sort-icon', ['field' => 'uploaded'])
                </th>
                <th class="user-active__downloaded-header" wire:click="sortBy('downloaded')" role="columnheader button">
                    {{ __('torrent.downloaded') }}
                    @include('livewire.includes._sort-icon', ['field' => 'downloaded'])
                </th>
                <th class="user-active__left-header" wire:click="sortBy('left')" role="columnheader button">
                    {{ __('torrent.left') }}
                    @include('livewire.includes._sort-icon', ['field' => 'left'])
                </th>
                <th class="user-active__progress-header" wire:click="sortBy('progress')" role="columnheader button">
                    {{ __('torrent.progress') }}
                    @include('livewire.includes._sort-icon', ['field' => 'progress'])
                </th>
                <th class="user-active__created-at-header" wire:click="sortBy('created_at')" role="columnheader button">
                    {{ __('torrent.started') }}
                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                </th>
                <th class="user-active__updated-at-header" wire:click="sortBy('updated_at')" role="columnheader button">
                    {{ __('torrent.updated') }}
                    @include('livewire.includes._sort-icon', ['field' => 'updated_at'])
                </th>
                </thead>
                <tbody>
                @foreach ($actives as $active)
                    <tr>
                        <td>
                            <a class="user-active__name" href="{{ route('torrent', ['id' => $active->torrent_id]) }}">
                                {{ $active->name }}
                            </a>
                        </td>
                        <td class="user-active__seeders">
                            <a href="{{ route('peers', ['id' => $active->torrent_id]) }}">
                                <span class='text-green'>
                                    {{ $active->seeders }}
                                </span>
                            </a>
                        </td>
                        <td class="user-active__leechers">
                            <a href="{{ route('peers', ['id' => $active->torrent_id]) }}">
                                <span class='text-red'>
                                    {{ $active->leechers }}
                                </span>
                            </a>
                        </td>
                        <td class="user-active__times">
                            <a href="{{ route('history', ['id' => $active->torrent_id]) }}">
                                <span class='text-orange'>
                                    {{ $active->times_completed }}
                                </span>
                            </a>
                        </td>
                        <td class="user-active__agent">
                            {{ $active->agent ?: __('common.unknown') }}
                        </td>
                        <td class="user-active__ip">
                            {{ $active->ip ?: __('common.unknown') }}
                        </td>
                        <td class="user-active__port">
                            {{ $active->port ?: __('common.unknown') }}
                        </td>
                        @if (\config('announce.connectable_check'))
                            <td class="user-active__connectable">
                                @php
                                    $connectable = null;
                                    if (cache()->has('peers:connectable:'.$active->ip.'-'.$active->port.'-'.$active->agent)) {
                                        $connectable = cache()->get('peers:connectable:'.$active->ip.'-'.$active->port.'-'.$active->agent);
                                    }
                                @endphp
                                @if ($connectable === null)
                                    <i class="{{ config('other.font-awesome') }} text-blue fa-question" title="Unknown Connectable Status"></i>
                                @else
                                    @if ($connectable)
                                        <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Connectable"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not Connectable"></i>
                                    @endif
                                @endif
                            </td>
                        @endif
                        <td class="user-active__seeding">
                            @if ($active->seeder === 1)
                                <i class="{{ config('other.font-awesome') }} text-green fa-check" title="{{ __('torrent.seeding') }}"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not {{ __('torrent.seeding') }}"></i>
                            @endif
                        </td>
                        <td class="user-active__size">
                            {{ App\Helpers\StringHelper::formatBytes($active->size) }}
                        </td>
                        <td class="user-active__uploaded text-green">
                            {{ App\Helpers\StringHelper::formatBytes($active->uploaded, 2) }}
                        </td>
                        <td class="user-active__downloaded text-red">
                            {{ App\Helpers\StringHelper::formatBytes($active->downloaded, 2) }}
                        </td>
                        <td class="user-active__left">
                            {{ App\Helpers\StringHelper::formatBytes($active->left, 2) }}
                        </td>
                        <td
                            class="user-active__progress"
                            title="{{ __('torrent.progress') }}: {{ $active->progress * 100 }}%"
                        >
                            {{ $active->progress < 100 ? \floor($active->progress * 10000) / 100 : INF }}%
                        </td>
                        @if ($showMorePrecision)
                            <td class="user-active__created-at">{{ $active->created_at ?? 'N/A' }}</td>
                            <td class="user-active__updated-at">{{ $active->updated_at ?? 'N/A' }}</td>
                        @else
                            <td class="user-active__created-at">
                                {{ isset($active->created_at) ? \explode(" ", $active->created_at)[0] : 'N/A' }}
                            </td>
                            <td class="user-active__updated-at">
                                {{ isset($active->updated_at) ? \explode(" ", $active->updated_at)[0] : 'N/A' }}
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $actives->links() }}
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