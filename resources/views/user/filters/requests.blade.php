<!-- Requests -->
<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <th class="torrents-icon">@lang('torrent.category')</th>
        <th>@lang('torrent.type')</th>
        <th class="torrents-filename col-sm-6">@lang('request.request')</th>
        <th>@lang('request.votes')</th>
        <th>@lang('common.comments')</th>
        <th>@lang('request.bounty')</th>
        <th>@lang('request.age')</th>
        <th>@lang('request.claimed') / @lang('request.filled')</th>
        </thead>
        <tbody>
        @foreach ($torrentRequests as $torrentRequest)
            <tr>
                <td>
                    <div class="text-center">
                        <i class="{{ $torrentRequest->category->icon }} torrent-icon" data-toggle="tooltip"
                           data-original-title="{{ $torrentRequest->category->name }} @lang('request.request')"></i>
                    </div>
                </td>
                <td>
                    <span class="label label-success">
                        {{ $torrentRequest->type }}
                    </span>
                </td>
                <td>
                    <a class="view-torrent" href="{{ route('request', ['id' => $torrentRequest->id]) }}">
                        {{ $torrentRequest->name }}
                    </a>
                </td>
                <td>
                    <span class="badge-user">
                        {{ $torrentRequest->votes }}
                    </span>
                </td>
                <td>
                    <span class="badge-user">
                        {{ $torrentRequest->comments->count() }}
                    </span>
                </td>
                <td>
                    <span class="badge-user">
                        {{ $torrentRequest->bounty }}
                    </span>
                </td>
                <td>
                    <span>
                        {{ $torrentRequest->created_at->diffForHumans() }}
                    </span>
                </td>
                <td>
                    @if ($torrentRequest->claimed != null && $torrentRequest->filled_hash == null)
                        <button class="btn btn-xs btn-primary">
                            <i class="{{ config('other.font-awesome') }} fa-suitcase"></i> @lang('request.claimed')
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