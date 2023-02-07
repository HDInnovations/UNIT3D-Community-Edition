<div class="container-fluid">
    <div class="block">
        <div class="container well search mt-5">
            <div class="form-horizontal form-condensed form-torrent-search form-bordered">
                <div class="mx-0 mt-5 form-group fatten-me">
                    <label for="name"
                            class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.name') }}</label>
                    <div class="col-sm-9 fatten-me">
                        <label for="search"></label><input type="text" class="form-control userFilter"
                                                            trigger="keyup"
                                                            id="search" placeholder="{{ __('torrent.name') }}">
                    </div>
                </div>
                <div class="mx-0 mt-5 form-group fatten-me">
                    <div class="mt-5 col-sm-1 label label-default fatten-me">
                        {{ __('torrent.filters') }}
                    </div>
                    <div class="col-sm-10">
                        <span class="badge-user">
                            <label style="user-select: none" class="inline" x-data="{ state: @entangle('rewarded'), ...ternaryCheckbox() }">
                                <input
                                    type="checkbox"
                                    class="user-torrents__checkbox"
                                    x-init="updateTernaryCheckboxProperties($el, state)"
                                    x-on:click="state = getNextTernaryCheckboxState(state); updateTernaryCheckboxProperties($el, state)"
                                    x-bind:checked="state === 'include'"
                                >
                                {{ __('graveyard.rewarded') }}
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <span id="filterHeader"></span>
        <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="resurrections">
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <th class="user-resurrections__name-header" wire:click="sortBy('name')" role="columnheader button">
                            {{ __('torrent.name') }}
                            @include('livewire.includes._sort-icon', ['field' => 'name'])
                        </th>
                        <th class="user-resurrections__size-header" wire:click="sortBy('size')" role="columnheader button">
                            {{ __('torrent.size') }}
                            @include('livewire.includes._sort-icon', ['field' => 'size'])
                        </th>
                        <th class="user-resurrections__seeders-header" wire:click="sortBy('seeders')" role="columnheader button">
                            {{ __('torrent.seeders') }}
                            @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                        </th>
                        <th class="user-resurrections__leechers-header" wire:click="sortBy('leechers')" role="columnheader button">
                            {{ __('torrent.leechers') }}
                            @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                        </th>
                        <th class="user-resurrections__times-completed-header" wire:click="sortBy('times_completed')" role="columnheader button">
                            {{ __('torrent.completed') }}
                            @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                        </th>
                        <th class="user-resurrections__created-at-header" wire:click="sortBy('created_at')" role="columnheader button">
                            {{ __('graveyard.resurrect-date') }}
                            @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                        </th>
                        <th class="user-resurrections__current-seedtime-header">
                            {{ __('graveyard.current-seedtime') }}
                        </th>
                        <th class="user-resurrections__seedtime-header" wire:click="sortBy('seedtime')" role="columnheader button">
                            {{ __('graveyard.seedtime-goal') }}
                            @include('livewire.includes._sort-icon', ['field' => 'seedtime'])
                        </th>
                        <th class="user-resurrections__rewarded-header" wire:click="sortBy('rewarded')" role="columnheader button">
                            {{ __('graveyard.rewarded') }}
                            @include('livewire.includes._sort-icon', ['field' => 'rewarded'])
                        </th>
                        <th class="user-resurrections__cancel-header">
                            {{ __('common.cancel') }}
                        </th>
                    </thead>
                    <tbody>
                    @foreach ($resurrections as $resurrection)
                        <tr>
                            <td>
                                <div class="torrent-file">
                                    <div>
                                        <a class="view-torrent"
                                            href="{{ route('torrent', ['id' => $resurrection->torrent->id]) }}">
                                            {{ $resurrection->torrent->name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                    <span class="badge-extra text-blue text-bold">
                                        {{ $resurrection->torrent->getSize() }}</span>
                            </td>
                            <td>
                                    <span class="badge-extra text-green text-bold">
                                        {{ $resurrection->torrent->seeders }}</span>
                            </td>
                            <td>
                                    <span class="badge-extra text-red text-bold">
                                        {{ $resurrection->torrent->leechers }}</span>
                            </td>
                            <td>
                                    <span class="badge-extra text-orange text-bold">
                                        {{ $resurrection->torrent->times_completed }} {{ __('common.times') }}</span>
                            </td>
                            <td>
                                {{ $resurrection->created_at->diffForHumans() }}
                            </td>
                            <td>
                                @php $torrent = App\Models\Torrent::where('id', '=',
                                    $resurrection->torrent_id)->pluck('id') @endphp
                                @php $history = App\Models\History::select(['seedtime'])->where('user_id', '=',
                                    $user->id)->where('torrent_id', '=', $torrent)->first() @endphp
                                {{ empty($history) ? '0' : App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
                            </td>
                            <td>
                                {{ App\Helpers\StringHelper::timeElapsed($resurrection->seedtime) }}
                            </td>
                            <td>
                                @if ($resurrection->rewarded == 0)
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('graveyard.destroy', ['id' => $resurrection->id]) }}"
                                        method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" @if ($resurrection->rewarded ==
                                            1) disabled @endif>
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $resurrections->links() }}
                </div>
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
