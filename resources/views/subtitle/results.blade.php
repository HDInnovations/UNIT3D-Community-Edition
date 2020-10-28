<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <tr>
            <th class="torrents-icon"></th>
            <th class="torrents-filename">@lang('torrent.torrent')</th>
            <th>@lang('common.language')</th>
            <th>@lang('common.download')</th>
            <th>@lang('subtitle.extension')</th>
            <th>@lang('subtitle.size')</th>
            <th>@lang('subtitle.downloads')</th>
            <th>@lang('subtitle.uploaded')</th>
            <th>@lang('subtitle.uploader')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subtitles as $subtitle)
            <tr>
                <td>
                    @if ($subtitle->torrent->category->image != null)
                        <a href="{{ route('categories.show', ['id' => $subtitle->torrent->category->id]) }}">
                            <div class="text-center">
                                <img src="{{ url('files/img/' . $subtitle->torrent->category->image) }}" data-toggle="tooltip"
                                     data-original-title="{{$subtitle->torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                     alt="{{ $subtitle->torrent->category->name }}">
                            </div>
                        </a>
                    @else
                        <a href="{{ route('categories.show', ['id' => $subtitle->torrent->category->id]) }}">
                            <div class="text-center">
                                <i class="{{ $subtitle->torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                   data-original-title="{{ $subtitle->torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"></i>
                            </div>
                        </a>
                    @endif
                </td>
                <td>
                    <a class="movie-title" href="{{ route('torrent', ['id' => $subtitle->torrent->id]) }}">
                        {{ $subtitle->torrent->name }}
                    </a>
                </td>
                <td>
                    {{ $subtitle->language->name }}
                    <i class="{{ config("other.font-awesome") }} fa-closed-captioning" data-toggle="tooltip" data-title="{{ $subtitle->note }}"></i>
                </td>
                <td>
                    <a href="{{ route('subtitles.download', ['id' => $subtitle->id]) }}" class="btn btn-xs btn-warning">@lang('common.download')</a>
                </td>
                <td>{{ $subtitle->extension }}</td>
                <td>{{ $subtitle->getSize() }}</td>
                <td>{{ $subtitle->downloads }}</td>
                <td>{{ $subtitle->created_at->diffForHumans() }}</td>
                <td>
                    @if ($subtitle->anon == true)
                        <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }}
                            @if (auth()->user()->id == $subtitle->user_id || auth()->user()->group->is_modo)
                                <a href="{{ route('users.show', ['username' => $subtitle->user->username]) }}">
                                    ({{ $subtitle->user->username }})
                                </a>
                            @endif
                        </span>
                    @else
                        <a href="{{ route('users.show', ['username' => $subtitle->user->username]) }}">
                            <span class="badge-user text-bold" style="color:{{ $subtitle->user->group->color }}; background-image:{{ $subtitle->user->group->effect }};">
                                <i class="{{ $subtitle->user->group->icon }}" data-toggle="tooltip" data-original-title="{{ $subtitle->user->group->name }}"></i> {{ $subtitle->user->username }}
                            </span>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $subtitles->links() }}
    </div>
</div>