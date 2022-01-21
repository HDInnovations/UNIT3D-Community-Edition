<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <th>{{ __('torrent.name') }}</th>
        <th>{{ __('torrent.seeding') }}</th>
        <th>{{ __('torrent.size') }}</th>
        <th>{{ __('torrent.seeders') }}</th>
        <th>{{ __('torrent.leechers') }}</th>
        <th>{{ __('torrent.completed') }}</th>
        <th>{{ __('torrent.completed_at') }}</th>
        <th>{{ __('torrent.satisfied_in') }}</th>
        <th>{{ __('torrent.seedtime') }}</th>
        <th>{{ __('torrent.status') }}</th>
        </thead>
        <tbody>
        @foreach ($downloads as $download)
            <tr class="userFiltered" active="{{ $download->active ? '1' : '0' }}"
                seeding="{{ $download->seeder == 1 ? '1' : '0' }}" prewarned="{{ $download->prewarn ? '1' : '0' }}"
                hr="{{ $download->hitrun ? '1' : '0' }}" immune="{{ $download->immune ? '1' : '0' }}">
                <td>
                    <a class="view-torrent" href="{{ route('torrent', ['id' => $download->torrent->id]) }}">
                        {{ $download->torrent->name }}
                    </a>
                </td>
                @if ($download->seeder == 1)
                    <td class="text-green">{{ __('common.yes') }}</td> @else
                    <td class="text-red">{{ __('common.no') }}</td> @endif
                <td>
                    <span class="badge-extra text-blue text-bold"> {{ $download->torrent->getSize() }}</span>
                </td>
                <td>
                    <span class="badge-extra text-green text-bold"> {{ $download->torrent->seeders }}</span>
                </td>
                <td>
                    <span class="badge-extra text-red text-bold"> {{ $download->torrent->leechers }}</span>
                </td>
                <td>
                        <span class="badge-extra text-orange text-bold"> {{ $download->torrent->times_completed }}
                            {{ __('common.times') }}</span>
                </td>
                <td>{{ $download->completed_at && $download->completed_at != null ? $download->completed_at->diffForHumans() : 'N/A' }}
                </td>
                @if ($download->seedtime < config('hitrun.seedtime'))
                    <td>
                            <span
                                    class="badge-extra text-red">{{ App\Helpers\StringHelper::timeRemaining($download->seedtime) }}</span>
                    </td>
                @else
                    <td>
                                <span
                                        class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                    </td>
                @endif
                @if ($download->seedtime < config('hitrun.seedtime'))
                    <td>
                                <span
                                        class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                    </td>
                @else
                    <td>
                                    <span
                                            class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                    </td>
                @endif
                <td>
                    @if ($download->seeder == 1)
                        <span class='label label-success'>{{ strtoupper(__('torrent.downloaded')) }}</span>
                    @else
                        <span class='label label-danger'>{{ strtoupper(__('torrent.not-downloaded')) }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $downloads->links() }}
    </div>
</div>
