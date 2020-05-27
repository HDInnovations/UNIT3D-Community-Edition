<div class="table-responsive">
    <table class="table table-condensed table-hover table-bordered">
        <thead>
            <tr>
                <th class="torrents-icon"></th>
                <th class="torrents-filename">@lang('torrent.name')</th>
                <th>@lang('torrent.age')</th>
                <th>@lang('torrent.size')</th>
                <th>@lang('torrent.short-seeds')</th>
                <th>@lang('torrent.short-leechs')</th>
                <th>@lang('torrent.short-completed')</th>
                <th>@lang('graveyard.resurrect')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($torrents as $torrent)
                @php $history = DB::table('history')->where('info_hash', '=', $torrent->info_hash)->where('user_id', '=',
                $user->id)->first(); @endphp
                <tr class="">
                    <td>
                        <div class="text-center">
                            <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                data-original-title="{{ $torrent->category->name }} @lang('torrent.torrent')"></i>
                        </div>
                    </td>
                    <td>
                        <div class="torrent-file">
                            <div>
                                <a class="view-torrent" href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                    {{ $torrent->name }}
                                </a>
                                <span class="label label-success">{{ $torrent->type->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span data-toggle="tooltip" data-original-title="">
                            {{ $torrent->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <span>
                            {{ $torrent->getSize() }}
                        </span>
                    </td>
                    <td>
                        {{ $torrent->seeders }}
                    </td>
                    <td>
                        {{ $torrent->leechers }}
                    </td>
                    <td>
                        {{ $torrent->times_completed }}
                    </td>
                    <td>
                        @php $resurrected = DB::table('graveyard')->where('torrent_id', '=',
                        $torrent->id)->where('rewarded', '=', 0)->first(); @endphp
                        @if (!$resurrected)
                            <button data-toggle="modal" data-target="#resurrect-{{ $torrent->id }}"
                                class="btn btn-sm btn-default">
                                <span class="icon">
                                    @emojione(':zombie:') @lang('graveyard.resurrect')
                                </span>
                            </button>
                            <div class="modal fade" id="resurrect-{{ $torrent->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="resurrect">
                                <div class="modal-dialog modal-dark" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h2>
                                                <i
                                                    class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>@lang('graveyard.resurrect')
                                                {{ strtolower(trans('torrent.torrent')) }} ?
                                            </h2>
                                        </div>
        
                                        <div class="modal-body">
                                            <p class="text-center text-bold">
                                                <span class="text-green">
                                                    <em>{{ $torrent->name }} </em>
                                                </span>
                                            </p>
                                            <p class="text-center text-bold">
                                                @lang('graveyard.howto')
                                            </p>
                                            <br>
                                            <div class="text-center">
                                                <p>{!! trans('graveyard.howto-desc1', ['name' => $torrent->name]) !!}
                                                    <span class="text-red text-bold">
                                                        @if (!$history)
                                                            0
                                                        @else
                                                            {{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
                                                        @endif
                                                    </span>
                                                    {{ strtolower(trans('graveyard.howto-hits')) }}
                                                    <span class="text-red text-bold">
                                                        @if (!$history)
                                                            {{ App\Helpers\StringHelper::timeElapsed(config('graveyard.time')) }}
                                                        @else
                                                            {{ App\Helpers\StringHelper::timeElapsed($history->seedtime + config('graveyard.time')) }}
                                                        @endif
                                                    </span>
                                                    {{ strtolower(trans('graveyard.howto-desc2')) }}
                                                    <span class="badge-user text-bold text-pink"
                                                        style="background-image:url(/img/sparkels.gif);">
                                                        {{ config('graveyard.reward') }} @lang('torrent.freeleech') Token(s)!
                                                    </span>
                                                </p>
                                                <div class="btns">
                                                    <form id="resurrect-torrent" role="form" method="POST"
                                                        action="{{ route('graveyard.store', ['id' => $torrent->id]) }}">
                                                        @csrf
                                                        @if (!$history)
                                                            <label for="seedtime"></label><input hidden="seedtime" name="seedtime"
                                                                id="seedtime" value="{{ config('graveyard.time') }}">
                                                        @else
                                                            <label for="seedtime"></label><input hidden="seedtime" name="seedtime"
                                                                id="seedtime"
                                                                value="{{ $history->seedtime + config('graveyard.time') }}">
                                                        @endif
                                                        <button type="submit" class="btn btn-success">
                                                            @lang('graveyard.resurrect') !
                                                        </button>
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                                                            @lang('common.cancel')
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button class="btn btn-sm btn-info" disabled>
                                <span class="icon">
                                    @emojione(':angel_tone2:') {{ strtolower(trans('graveyard.pending')) }}
                                </span>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $torrents->links() }}
    </div>
</div>
