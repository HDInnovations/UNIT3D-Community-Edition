<div style="display: flex; flex-direction: column; row-gap: 1rem;">
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.search') }}</h2>
        </header>
        <div class="panel__body" style="padding: 5px;">
            <form class="form">
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input wire:model="torrent" class="form__text" placeholder=" ">
                        <label class="form__label form__label--floating">Torrent Name</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="user" class="form__text" placeholder=" ">
                        <label class="form__label form__label--floating">Username</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="agent" class="form__text" placeholder=" ">
                        <label class="form__label form__label--floating">Agent</label>
                    </p>
                    <p class="form__group">
                        <select wire:model="seeder" class="form__select" placeholder=" ">
                            <option value="any">Any</option>
                            <option value="include">Completed</option>
                            <option value="exclude">Incomplete</option>
                        </select>
                        <label class="form__label form__label--floating">Completed</label>
                    </p>
                    <p class="form__group">
                        <select wire:model="active" class="form__select" placeholder=" ">
                            <option value="any">Any</option>
                            <option value="exclude">Inactive</option>
                            <option value="include">Active</option>
                        </select>
                        <label class="form__label form__label--floating">Active</label>
                    </p>
                    <p class="form__group">
                        <select wire:model="groupBy" class="form__select" placeholder=" ">
                            <option value="none">None</option>
                            <option value="user_id">User</option>
                        </select>
                        <label class="form__label form__label--floating">Group By</label>
                    </p>
                </div>
            </form>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Histories</h2>
        <div class="panel__body" wire:loading.block>
            Loading...
        </div>
        <div class="data-table-wrapper">
            @switch ($groupBy)
                @case('user_id')
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('histories.user_id')" role="columnheader button">
                                    {{ __('user.user') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.user_id'])
                                </th>
                                <th wire:click="sortBy('histories.torrent_count')" role="columnheader button">
                                    {{ __('torrent.torrents') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'torrent_count'])
                                </th>
                                <th wire:click="sortBy('histories.uploaded_sum')" role="columnheader button">
                                    {{ __('user.credited-upload') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.uploaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.actual_uploaded_sum')" role="columnheader button">
                                    {{ __('user.upload-true') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.actual_uploaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.client_uploaded_sum')" role="columnheader button">
                                    Client Upload
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.client_uploaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.downloaded_sum')" role="columnheader button">
                                    {{ __('user.credited-download') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.downloaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.actual_downloaded_sum')" role="columnheader button">
                                    {{ __('user.download-true') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.actual_downloaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.client_downloaded_sum')" role="columnheader button">
                                    Client Download
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.client_downloaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.refunded_downloaded_sum')" role="columnheader button">
                                    {{ __('torrent.refunded') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.refunded_downloaded_sum'])
                                </th>
                                <th wire:click="sortBy('histories.created_at_min')" role="columnheader button">
                                    {{ __('torrent.started') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.created_at_min'])
                                </th>
                                <th wire:click="sortBy('histories.updated_at_max')" role="columnheader button">
                                    Announced
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.updated_at_max'])
                                </th>
                                <th wire:click="sortBy('histories.seedtime_avg')" role="columnheader button">
                                    {{ __('user.avg-seedtime') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.seedtime_avg'])
                                </th>
                                <th wire:click="sortBy('seeding_count')" role="columnheader button">
                                    {{ __('torrent.seeding') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'seeding_count'])
                                </th>
                                <th wire:click="sortBy('leeching_count')" role="columnheader button">
                                    {{ __('torrent.leeching') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'leeching_count'])
                                </th>
                                <th wire:click="sortBy('prewarn_count')" role="columnheader button" title="{{ __('torrent.prewarn') }}">
                                    <i class="fas fa-exclamation"></i>
                                </th>
                                <th wire:click="sortBy('hitrun_count')" role="columnheader button" title="{{ __('torrent.hitrun') }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </th>
                                <th wire:click="sortBy('immune_count')" role="columnheader button" title="{{ __('torrent.immune') }}">
                                    <i class="fas fa-syringe"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        <x-user_tag :user="$history->user" :anon="false" />
                                    </td>
                                    <td>
                                        {{ $history->torrent_count }}
                                    </td>
                                    <td title="{{ $history->uploaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->uploaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->actual_uploaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->actual_uploaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->client_uploaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->client_uploaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->downloaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->downloaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->actual_downloaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->actual_downloaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->client_downloaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->client_downloaded_sum, 2) }}
                                    </td>
                                    <td title="{{ $history->refunded_downloaded_sum }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->refunded_downloaded_sum, 2) }}
                                    </td>
                                    <td>
                                        <time datetime="{{ $history->created_at_min }}" title="{{ $history->created_at_min }}">
                                            {{ $history->created_at_min ? $history->created_at_min->diffForHumans() : 'N/A' }}
                                        </time>
                                    </td>
                                    <td>
                                        <time datetime="{{ $history->updated_at_max }}" title="{{ $history->updated_at_max }}">
                                            {{ $history->updated_at_max ? $history->updated_at_max->diffForHumans() : 'N/A' }}
                                        </time>
                                    </td>
                                    @if ($history->seedtime < config('hitrun.seedtime'))
                                        <td class="text-red" title="{{ App\Helpers\StringHelper::timeElapsed($history->seedtime_avg) }}">
                                            {{ $weeks = intdiv($history->seedtime_avg ?? 0, 3600 * 24 * 7) }}w {{ intdiv(($history->seedtime_avg ?? 0) - $weeks * 3600 * 24 * 7, 3600) }}h
                                        </td>
                                    @else
                                        <td class="text-green" title="{{ App\Helpers\StringHelper::timeElapsed($history->seedtime_avg) }}">
                                            {{ $weeks = intdiv($history->seedtime_avg ?? 0, 3600 * 24 * 7) }}w {{ intdiv(($history->seedtime_avg ?? 0) - $weeks * 3600 * 24 * 7, 3600) }}h
                                        </td>
                                    @endif
                                    <td>{{ $history->seeding_count }}</td>
                                    <td>{{ $history->leeching_count }}</td>
                                    <td>{{ $history->immune_count }}</td>
                                    <td>{{ $history->hitrun_count }}</td>
                                    <td>{{ $history->prewarn_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @break
                @default
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('histories.user_id')" role="columnheader button">
                                    {{ __('user.user') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.user_id'])
                                </th>
                                <th wire:click="sortBy('histories.torrent_id')" role="columnheader button">
                                    {{ __('torrent.torrent') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.torrent_id'])
                                </th>
                                <th wire:click="sortBy('histories.agent')" role="columnheader button">
                                    {{ __('torrent.agent') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.agent'])
                                </th>
                                <th wire:click="sortBy('histories.uploaded')" role="columnheader button">
                                    {{ __('user.credited-upload') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.uploaded'])
                                </th>
                                <th wire:click="sortBy('histories.actual_uploaded')" role="columnheader button">
                                    {{ __('user.upload-true') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.actual_uploaded'])
                                </th>
                                <th wire:click="sortBy('histories.client_uploaded')" role="columnheader button">
                                    Client Upload
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.client_uploaded'])
                                </th>
                                <th wire:click="sortBy('histories.downloaded')" role="columnheader button">
                                    {{ __('user.credited-download') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.downloaded'])
                                </th>
                                <th wire:click="sortBy('histories.actual_downloaded')" role="columnheader button">
                                    {{ __('user.download-true') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.actual_downloaded'])
                                </th>
                                <th wire:click="sortBy('histories.client_downloaded')" role="columnheader button">
                                    Client Download
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.client_downloaded'])
                                </th>
                                <th wire:click="sortBy('histories.refunded_downloaded')" role="columnheader button">
                                    {{ __('torrent.refunded') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.refunded_downloaded'])
                                </th>
                                <th wire:click="sortBy('histories.created_at')" role="columnheader button">
                                    {{ __('torrent.started') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.created_at'])
                                </th>
                                <th wire:click="sortBy('histories.updated_at')" role="columnheader button">
                                    Announced
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.updated_at'])
                                </th>
                                <th wire:click="sortBy('histories.completed_at')" role="columnheader button">
                                    {{ __('torrent.completed_at') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.completed_at'])
                                </th>
                                <th wire:click="sortBy('histories.seedtime')" role="columnheader button">
                                    {{ __('torrent.seedtime') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'histories.seedtime'])
                                </th>
                                <th wire:click="sortBy('seeding')" role="columnheader button">
                                    {{ __('torrent.seeding') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'seeding'])
                                </th>
                                <th wire:click="sortBy('leeching')" role="columnheader button">
                                    {{ __('torrent.leeching') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'leeching'])
                                </th>
                                <th wire:click="sortBy('prewarn')" role="columnheader button" title="{{ __('torrent.prewarn') }}">
                                    <i class="fas fa-exclamation"></i>
                                </th>
                                <th wire:click="sortBy('hitrun')" role="columnheader button" title="{{ __('torrent.hitrun') }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </th>
                                <th wire:click="sortBy('immune')" role="columnheader button" title="{{ __('torrent.immune') }}">
                                    <i class="fas fa-syringe"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        <x-user_tag :user="$history->user" :anon="false" />
                                    </td>
                                    <td>
                                        <a href="{{ route('torrents.show', ['id' => $history->torrent_id]) }}">
                                            {{ $history->torrent->name ?? '' }}
                                        </a>
                                    </td>
                                    <td>{{ $history->agent }}</td>
                                    <td title="{{ $history->uploaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->uploaded, 2) }}
                                    </td>
                                    <td title="{{ $history->actual_uploaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->actual_uploaded, 2) }}
                                    </td>
                                    <td title="{{ $history->client_uploaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->client_uploaded, 2) }}
                                    </td>
                                    <td title="{{ $history->downloaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->downloaded, 2) }}
                                    </td>
                                    <td title="{{ $history->actual_downloaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->actual_downloaded, 2) }}
                                    </td>
                                    <td title="{{ $history->client_downloaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->client_downloaded, 2) }}
                                    </td>
                                    <td title="{{ $history->refunded_downloaded }}">
                                        {{ App\Helpers\StringHelper::formatBytes($history->refunded_downloaded, 2) }}
                                    </td>
                                    <td>
                                        <time datetime="{{ $history->created_at }}" title="{{ $history->created_at }}">
                                            {{ $history->created_at ? $history->created_at->diffForHumans() : 'N/A' }}
                                        </time>
                                    </td>
                                    <td>
                                        <time datetime="{{ $history->updated_at }}" title="{{ $history->updated_at }}">
                                            {{ $history->updated_at ? $history->updated_at->diffForHumans() : 'N/A' }}
                                        </time>
                                    </td>
                                    <td>
                                        <time datetime="{{ $history->completed_at }}" title="{{ $history->completed_at }}">
                                            {{ $history->completed_at ? $history->completed_at->diffForHumans() : 'N/A' }}
                                        </time>
                                    </td>
                                    @if ($history->seedtime < config('hitrun.seedtime'))
                                        <td class="text-red" title="{{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }}">
                                            {{ $weeks = intdiv($history->seedtime ?? 0, 3600 * 24 * 7) }}w {{ intdiv(($history->seedtime ?? 0) - $weeks * 3600 * 24 * 7, 3600) }}h
                                        </td>
                                    @else
                                        <td class="text-green" title="{{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }}">
                                            {{ $weeks = intdiv($history->seedtime ?? 0, 3600 * 24 * 7) }}w {{ intdiv(($history->seedtime ?? 0) - $weeks * 3600 * 24 * 7, 3600) }}h
                                        </td>
                                    @endif
                                    <td>
                                        @if ($history->active)
                                            <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Active"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Inactive"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($history->seeder)
                                            <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Seeder"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Leecher"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($history->immune)
                                            <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Immune"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not immune"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($history->hitrun)
                                            <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Warned"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not warned"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($history->prewarn)
                                            <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Prewarned"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not Prewarned"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            @endswitch
            {{ $histories->links('partials.pagination') }}
        </div>
    </section>
</div>
