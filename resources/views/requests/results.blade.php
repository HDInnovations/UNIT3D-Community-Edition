<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <tr>
            <th class="torrents-icon">{{ trans('torrent.category') }}</th>
            <th class="torrents-filename col-sm-6">{{ trans('request.request') }}</th>
            <th>{{ trans('common.author') }}</th>
            <th>{{ trans('request.votes') }}</th>
            <th>{{ trans('common.comments') }}</th>
            <th>{{ trans('request.bounty') }}</th>
            <th>{{ trans('request.age') }}</th>
            <th>{{ trans('request.claimed') }} / {{ trans('request.filled') }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($torrentRequests as $torrentRequest)
                <tr>
                <td>
                    <div class="text-center">
                        <i class="{{ $torrentRequest->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                           data-original-title="{{ $torrentRequest->category->name }} {{ trans('request.request') }}"></i>
                    </div>
                </td>
                <td>
                    <a class="view-torrent" data-id="{{ $torrentRequest->id }}" href="{{ route('request', ['id' => $torrentRequest->id]) }}">
                        {{ $torrentRequest->name }}
                    </a>
                    <span class="label label-success">
                        {{ $torrentRequest->type }}
                    </span>
                </td>
                <td>
                    <span class="badge-user">
                        <a href="{{ route('profile', ['username' => $torrentRequest->user->username, 'id' => $torrentRequest->user->id]) }}">
                            {{ $torrentRequest->user->username }}
                        </a>
                    </span>
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
                            <i class="{{ config('other.font-awesome') }} fa-suitcase"></i> {{ trans('request.claimed') }}
                        </button>
                    @elseif ($torrentRequest->filled_hash != null && $torrentRequest->approved_by == null)
                        <button class="btn btn-xs btn-info">
                            <i class="{{ config('other.font-awesome') }} fa-question-circle"></i> {{ trans('request.pending') }}
                        </button>
                    @elseif ($torrentRequest->filled_hash == null)
                        <button class="btn btn-xs btn-danger">
                            <i class="{{ config('other.font-awesome') }} fa-times-circle"></i> {{ trans('request.unfilled') }}
                        </button>
                    @else
                        <button class="btn btn-xs btn-success">
                            <i class="{{ config('other.font-awesome') }} fa-check-circle"></i> {{ trans('request.filled') }}
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
