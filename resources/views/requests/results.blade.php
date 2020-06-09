<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
            <tr>
                <th class="torrents-icon">@lang('torrent.category')</th>
                <th>@lang('torrent.type')/@lang('torrent.resolution')</th>
                <th class="torrents-filename col-sm-6">@lang('request.request')</th>
                <th>@lang('common.author')</th>
                <th>@lang('request.votes')</th>
                <th>@lang('common.comments')</th>
                <th>@lang('request.bounty')</th>
                <th>@lang('request.age')</th>
                <th>@lang('request.claimed') / @lang('request.filled')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($torrentRequests as $torrentRequest)
                <tr>
                    <td style="vertical-align: middle;">
                        @if ($torrentRequest->category->image != null)
                            <a href="{{ route('categories.show', ['id' => $torrentRequest->category->id]) }}">
                                <div class="text-center">
                                    <img src="{{ url('files/img/' . $torrentRequest->category->image) }}" data-toggle="tooltip"
                                        data-original-title="{{ $torrentRequest->category->name }} {{ strtolower(trans('request.request')) }}"
                                        style="padding-bottom: 6px;" alt="{{ $torrentRequest->category->name }}">
                                </div>
                            </a>
                        @else
                            <a href="{{ route('categories.show', ['id' => $torrentRequest->category->id]) }}">
                                <div class="text-center">
                                    <i class="{{ $torrentRequest->category->icon }} torrent-icon" data-toggle="tooltip"
                                        data-original-title="{{ $torrentRequest->category->name }} {{ strtolower(trans('request.request')) }}"
                                        style="padding-bottom: 6px;"></i>
                                </div>
                            </a>
                        @endif
                    </td>
                    <td style="width: 1%; vertical-align: middle;" >
                        <div class="text-center">
                            <span class="label label-success" data-toggle="tooltip"
                                  data-original-title="@lang('request.type')">
                                {{ $torrentRequest->type->name }}
                            </span>
                        </div>
                        <div class="text-center" style="padding-top: 8px;">
                            <span class="label label-success" data-toggle="tooltip"
                                  data-original-title="@lang('request.resolution')">
                                {{ $torrentRequest->resolution->name }}
                            </span>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <a class="view-torrent" href="{{ route('request', ['id' => $torrentRequest->id]) }}">
                            {{ $torrentRequest->name }}
                        </a>
                    </td>
                    <td style="vertical-align: middle;">
                        @if ($torrentRequest->anon == 0)
                            <span class="badge-user">
                                <a href="{{ route('users.show', ['username' => $torrentRequest->user->username]) }}">
                                    {{ $torrentRequest->user->username }}
                                </a>
                            </span>
                        @else
                            <span class="badge-user">{{ strtoupper(trans('common.anonymous')) }}
                                @if ($user->group->is_modo || $torrentRequest->user->username == $user->username)
                                    <a href="{{ route('users.show', ['username' => $torrentRequest->user->username]) }}">
                                        ({{ $torrentRequest->user->username }})
                                    </a>
                                @endif
                            </span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">
                        <span class="badge-user">
                            {{ $torrentRequest->votes }}
                        </span>
                    </td>
                    <td style="vertical-align: middle;">
                        <span class="badge-user">
                            {{ $torrentRequest->comments->count() }}
                        </span>
                    </td>
                    <td style="vertical-align: middle;">
                        <span class="badge-user">
                            {{ $torrentRequest->bounty }}
                        </span>
                    </td>
                    <td style="vertical-align: middle;">
                        <span>
                            {{ $torrentRequest->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td style="vertical-align: middle;">
                        @if ($torrentRequest->claimed != null && $torrentRequest->filled_hash == null)
                            <button class="btn btn-xs btn-primary">
                                <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i> @lang('request.claimed')
                            </button>
                        @elseif ($torrentRequest->filled_hash != null && $torrentRequest->approved_by == null)
                            <button class="btn btn-xs btn-info">
                                <i class="{{ config('other.font-awesome') }} fa-question-circle"></i> @lang('request.pending')
                            </button>
                        @elseif ($torrentRequest->filled_hash == null)
                            <button class="btn btn-xs btn-danger">
                                <i class="{{ config('other.font-awesome') }} fa-times-circle"></i> @lang('request.unfilled')
                            </button>
                        @else
                            <button class="btn btn-xs btn-success">
                                <i class="{{ config('other.font-awesome') }} fa-check-circle"></i> @lang('request.filled')
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $torrentRequests->links() }}
    </div>
</div>
