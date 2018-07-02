 <div class="table-responsive">
        <table class="table table-condensed table-hover table-bordered">
            <thead>
            <tr>
                <th class="torrents-icon"></th>
                <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                <th>{{ trans('torrent.age') }}</th>
                <th>{{ trans('torrent.size') }}</th>
                <th>{{ trans('torrent.short-seeds') }}</th>
                <th>{{ trans('torrent.short-leechs') }}</th>
                <th>{{ trans('torrent.short-completed') }}</th>
                <th>{{ trans('graveyard.ressurect') }}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($torrents as $torrent)
                @php $history = DB::table('history')->where('info_hash', '=', $torrent->info_hash)->where('user_id', '=', $user->id)->first(); @endphp
                <tr class="">
                    <td>
                        <div class="text-center">
                            <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                               data-original-title="{{ $torrent->category->name }} {{ trans('torrent.torrent') }}"></i>
                        </div>
                    </td>
                    <td>
                        <div class="torrent-file">
                            <div>
                                <a class="view-torrent" data-id="{{ $torrent->id }}" data-slug="{{ $torrent->slug }}"
                                   href="{{ route('torrent', array('slug' => $torrent->slug, 'id' => $torrent->id)) }}"
                                   data-toggle="tooltip" title=""
                                   data-original-title="{{ $torrent->name }}">{{ $torrent->name }} </a><span
                                        class="label label-success">{{ $torrent->type }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
            <span data-toggle="tooltip" title=""
                  data-original-title="">{{ $torrent->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <span class="">{{ $torrent->getSize() }}</span>
                    </td>
                    <td>{{ $torrent->seeders }}</td>
                    <td>{{ $torrent->leechers }}</td>
                    <td>{{ $torrent->times_completed }}</td>
                    <td>
                        @php $resurrected = DB::table('graveyard')->where('torrent_id', '=', $torrent->id)->first(); @endphp
                        @if(!$resurrected)
                            <button data-toggle="modal" data-target="#resurrect-{{ $torrent->id }}"
                                    class="btn btn-sm btn-default">
                                <span class="icon">@emojione(':zombie:') {{ trans('graveyard.ressurect') }}</span>
                            </button>
                            {{-- Resurrect Modal --}}
                            <div class="modal fade" id="resurrect-{{ $torrent->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="resurrect">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h2>
                                                <i class="fa fa-thumbs-up"></i>{{ trans('graveyard.ressurect') }} {{ strtolower(trans('torrent.torrent')) }}
                                                ?</h2>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-center text-bold"><span
                                                        class="text-green"><em>{{ $torrent->name }} </em></span>
                                            </p>
                                            <p class="text-center text-bold">{{ trans('graveyard.howto') }}</p>
                                            <br>
                                            <div class="text-center">
                                                <p>{!! trans('graveyard.howto-desc1', ['name' => $torrent->name]) !!}
                                                    <span class="text-red text-bold">@if(!$history)
                                                            0 @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }} @endif</span> {{ strtolower(trans('graveyard.howto-hits')) }}
                                                    <span class="text-red text-bold">@if(!$history) {{ App\Helpers\StringHelper::timeElapsed(config('graveyard.time')) }} @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime + config('graveyard.time')) }} @endif</span> {{ strtolower(trans('graveyard.howto-desc2')) }}
                                                    <span class="badge-user text-bold text-pink"
                                                          style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ config('graveyard.reward') }} {{ trans('torrent.freeleech') }}
                                                        Token(s)!</span></p>
                                                <div class="btns">
                                                    <form id="resurrect-torrent" role="form" method="POST"
                                                          action="{{ route('resurrect', ['torrent_id' => $torrent->id]) }}">
                                                        {{ csrf_field() }}
                                                        @if(!$history)
                                                            <input hidden="seedtime" name="seedtime" id="seedtime"
                                                                   value="{{ config('graveyard.time') }}">
                                                        @else
                                                            <input hidden="seedtime" name="seedtime" id="seedtime"
                                                                   value="{{ $history->seedtime + config('graveyard.time') }}">
                                                        @endif
                                                        <button type="submit"
                                                                class="btn btn-success">{{ trans('graveyard.ressurect') }}
                                                            !
                                                        </button>
                                                        <button type="button" class="btn btn-warning"
                                                                data-dismiss="modal">{{ trans('common.cancel') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button class="btn btn-sm btn-info" disabled>
                                <span class="icon">@emojione(':angel_tone2:') {{ strtolower(trans('graveyard.pending')) }}</span>
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


