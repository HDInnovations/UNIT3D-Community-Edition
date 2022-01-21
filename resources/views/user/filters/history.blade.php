<div class="block">
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
            <th>{{ __('torrent.name') }}</th>
            <th>{{ __('torrent.client') }}</th>
            <th>{{ __('common.connected') }}</th>
            <th>{{ __('torrent.seeder') }}</th>
            <th>{{ __('common.upload') }}</th>
            <th>{{ __('common.download') }}</th>
            <th>{{ __('torrent.seedtime') }}</th>
            <th>{{ __('torrent.created_at') }}</th>
            <th>{{ __('torrent.updated_at') }}</th>
            <th>{{ __('torrent.completed_at') }}</th>
            <th>{{ __('torrent.prewarn') }}</th>
            <th>{{ __('torrent.hitrun') }}</th>
            <th>{{ __('torrent.immune') }}</th>
            </thead>
            <tbody>
            @foreach ($history as $his)
                <tr class="userFiltered" active="{{ $his->active ? '1' : '0' }}"
                    seeding="{{ $his->seeder == 1 ? '1' : '0' }}" prewarned="{{ $his->prewarn ? '1' : '0' }}"
                    hr="{{ $his->hitrun ? '1' : '0' }}" immune="{{ $his->immune ? '1' : '0' }}">
                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['id' => $his->torrent->id]) }}">
                            {{ $his->torrent->name }}
                        </a>
                    </td>
                    <td>
                            <span
                                    class="badge-extra text-purple">{{ $his->agent ?: __('common.unknown') }}</span>
                    </td>
                    @if ($his->active == 1)
                        <td class="text-green">{{ __('common.yes') }}</td> @else
                        <td class="text-red">{{ __('common.no') }}</td> @endif
                    @if ($his->seeder == 1)
                        <td class="text-green">{{ __('common.yes') }}</td> @else
                        <td class="text-red">{{ __('common.no') }}</td> @endif
                    <td>
                            <span
                                    class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his->actual_uploaded, 2) }}</span>
                        <span class="badge-extra text-blue" data-toggle="tooltip"
                              data-original-title="{{ __('user.credited-upload') }}">{{ App\Helpers\StringHelper::formatBytes($his->uploaded, 2) }}</span>
                    </td>
                    <td>
                            <span
                                    class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his->actual_downloaded, 2) }}</span>
                        <span class="badge-extra text-orange" data-toggle="tooltip"
                              data-original-title="{{ __('user.credited-download') }}">{{ App\Helpers\StringHelper::formatBytes($his->downloaded, 2) }}</span>
                    </td>
                    @if ($his->seedtime < config('hitrun.seedtime'))
                        <td>
                                <span
                                        class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                        </td>
                    @else
                        <td>
                                    <span
                                            class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                        </td>
                    @endif
                    <td>{{ $his->created_at ? $his->created_at->diffForHumans() : 'N/A' }}</td>
                    <td>{{ $his->updated_at ? $his->updated_at->diffForHumans() : 'N/A' }}</td>
                    <td>{{ $his->completed_at ? $his->completed_at->diffForHumans() : 'N/A' }}</td>
                    @if ($his->prewarn == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                    @if ($his->hitrun == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                    @if ($his->immune == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{ $history->links() }}
        </div>
    </div>
</div>
