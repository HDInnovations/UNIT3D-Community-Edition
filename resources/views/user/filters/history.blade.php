<div class="block">
    <!-- History -->
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <th>@lang('torrent.name')</th>
                <th>@lang('torrent.client')</th>
                <th>@lang('common.connected')</th>
                <th>@lang('torrent.seeder')</th>
                <th>@lang('common.upload')</th>
                <th>@lang('common.download')</th>
                <th>@lang('torrent.seedtime')</th>
                <th>@lang('torrent.created_at')</th>
                <th>@lang('torrent.updated_at')</th>
                <th>@lang('torrent.completed_at')</th>
                <th>@lang('torrent.prewarn')</th>
                <th>@lang('torrent.hitrun')</th>
                <th>@lang('torrent.immune')</th>
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
                                class="badge-extra text-purple">{{ $his->agent ? $his->agent : trans('common.unknown') }}</span>
                        </td>
                        @if ($his->active == 1)
                        <td class="text-green">@lang('common.yes')</td> @else
                        <td class="text-red">@lang('common.no')</td> @endif
                        @if ($his->seeder == 1)
                        <td class="text-green">@lang('common.yes')</td> @else
                        <td class="text-red">@lang('common.no')</td> @endif
                        <td>
                            <span
                                class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his->actual_uploaded, 2) }}</span>
                            <span class="badge-extra text-blue" data-toggle="tooltip"
                                data-original-title="@lang('user.credited-upload')">{{ App\Helpers\StringHelper::formatBytes($his->uploaded, 2) }}</span>
                        </td>
                        <td>
                            <span
                                class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his->actual_downloaded, 2) }}</span>
                            <span class="badge-extra text-orange" data-toggle="tooltip"
                                data-original-title="@lang('user.credited-download')">{{ App\Helpers\StringHelper::formatBytes($his->downloaded, 2) }}</span>
                        </td>
                        @if ($his->seedtime < config('hitrun.seedtime')) <td>
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
