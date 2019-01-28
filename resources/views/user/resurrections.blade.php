@extends('layout.default')

@section('title')
    <title>My Resurrections - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('myResurrections', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">My Resurrections</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
        <h1 class="title">
            {{ $user->username }} Resurrections
        </h1>
        <div class="table-responsive">
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                <tr>
                    <th class="torrents-icon">Category</th>
                    <th class="torrents-filename">@lang('torrent.name')</th>
                    <th>@lang('torrent.size')</th>
                    <th>@lang('torrent.short-seeds')</th>
                    <th>@lang('torrent.short-leechs')</th>
                    <th>@lang('torrent.short-completed')</th>
                    <th>Resurrect Date</th>
                    <th>Current Seedtime</th>
                    <th>Seedtime Goal</th>
                    <th>Rewarded</th>
                    <th>Cancel</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($resurrections as $resurrection)
                    <tr>
                        <td>
                            <div class="text-center">
                                <i class="{{ $resurrection->torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                   data-original-title="{{ $resurrection->torrent->category->name }} @lang('torrent.torrent')"></i>
                            </div>
                        </td>
                        <td>
                            <div class="torrent-file">
                                <div>
                                    <a class="view-torrent" href="{{ route('torrent', ['slug' => $resurrection->torrent->slug, 'id' => $resurrection->torrent->id]) }}">
                                        {{ $resurrection->torrent->name }}
                                    </a>
                                    <span class="label label-success">{{ $resurrection->torrent->type }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $resurrection->torrent->getSize() }}
                        </td>
                        <td>
                            {{ $resurrection->torrent->seeders }}
                        </td>
                        <td>
                            {{ $resurrection->torrent->leechers }}
                        </td>
                        <td>
                            {{ $resurrection->torrent->times_completed }}
                        </td>
                        <td>
                            {{ $resurrection->created_at->diffForHumans() }}
                        </td>
                        <td>
                            @php $torrent = App\Torrent::where('id', '=', $resurrection->torrent_id)->pluck('info_hash'); @endphp
                            @php $history = App\History::select(['seedtime'])->where('user_id', '=', $user->id)->where('info_hash', '=', $torrent)->first(); @endphp
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
        </div>
    </div>
@endsection

