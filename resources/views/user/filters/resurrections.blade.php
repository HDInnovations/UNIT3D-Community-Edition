<!-- Resurrections -->
<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <th>@lang('torrent.name')</th>
        <th>@lang('torrent.size')</th>
        <th>@lang('torrent.seeders')</th>
        <th>@lang('torrent.leechers')</th>
        <th>@lang('torrent.completed')</th>
        <th>@lang('graveyard.resurrect-date')</th>
        <th>@lang('graveyard.current-seedtime')</th>
        <th>@lang('graveyard.seedtime-goal')</th>
        <th>@lang('graveyard.rewarded')</th>
        <th>@lang('common.cancel')</th>
        </thead>
        <tbody>
        @foreach ($resurrections as $resurrection)
            <tr>
                <td>
                    <div class="torrent-file">
                        <div>
                            <a class="view-torrent" href="{{ route('torrent', ['slug' => $resurrection->torrent->slug, 'id' => $resurrection->torrent->id]) }}">
                                {{ $resurrection->torrent->name }}
                            </a>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge-extra text-blue text-bold"> {{ $resurrection->torrent->getSize() }}</span>
                </td>
                <td>
                    <span class="badge-extra text-green text-bold"> {{ $resurrection->torrent->seeders }}</span>
                </td>
                <td>
                    <span class="badge-extra text-red text-bold"> {{ $resurrection->torrent->leechers }}</span>
                </td>
                <td>
                    <span class="badge-extra text-orange text-bold"> {{ $resurrection->torrent->times_completed }} @lang('common.times')</span>
                </td>
                <td>
                    {{ $resurrection->created_at->diffForHumans() }}
                </td>
                <td>
                    @php $torrent = App\Models\Torrent::where('id', '=', $resurrection->torrent_id)->pluck('info_hash'); @endphp
                    @php $history = App\Models\History::select(['seedtime'])->where('user_id', '=', $user->id)->where('info_hash', '=', $torrent)->first(); @endphp
                    {{ (empty($history)) ? '0' : App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
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
                    <form action="{{ route('graveyard.destroy', ['id' => $resurrection->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" @if ($resurrection->rewarded == 1) disabled @endif>
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
