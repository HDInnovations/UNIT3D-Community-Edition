@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.bookmarks') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('bookmarks.index', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.bookmarks')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.other')
            <div class="header gradient red">
                <div class="inner_content">
                    <h1>{{ $user->username }} @lang('user.bookmarks')</h1>
                </div>
            </div>
            <div class="some-padding">
            <div class="table-responsive">
                <div class="pull-right"></div>
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-1">@lang('torrent.category') / @lang('torrent.type')</th>
                        <th>@lang('common.name')</th>
                        <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-cogs"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($bookmarks as $bookmark)
                        <tr>
                            <td>
                                <a href="{{ route('categories.show', ['id' => $bookmark->category->id]) }}">
                                    <div class="text-center">
                                        <i class="{{ $bookmark->category->icon }} torrent-icon" data-toggle="tooltip"
                                           data-original-title="{{ $bookmark->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                           style="padding-bottom: 6px;"></i>
                                    </div>
                                </a>
                                <div class="text-center">
                                    <span class="label label-success" data-toggle="tooltip" data-original-title="Type">
                                        {{ $bookmark->type }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <a class="view-torrent" href="{{ route('torrent', ['id' => $bookmark->id]) }}">
                                    {{ $bookmark->name }}
                                </a>
                                @if (config('torrent.download_check_page') == 1)
                                    <a href="{{ route('download_check', ['id' => $bookmark->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('download', ['id' => $bookmark->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @endif
                                
                                @php $history = \App\Models\History::where('user_id', '=', $user->id)->where('info_hash', '=', $bookmark->info_hash)->first(); @endphp
                                @if ($history)
                                    @if ($history->seeder == 1 && $history->active == 1)
                                        <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Currently Seeding!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 1)
                                        <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Currently Leeching!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                        <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Started Downloading But Never Completed!">
                                            <i class="{{ config('other.font-awesome') }} fa-spinner"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                        <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="You Completed This Download But Are No Longer Seeding It!">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                        </button>
                                    @endif
                                @endif

                                <br>
                                
                                @if ($bookmark->anon == 1)
                                    <span class="badge-extra text-bold">
                                        <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By ANONYMOUS USER
                                        @if ($user->id == $bookmark->user->id || $user->group->is_modo)
                                            <a href="{{ route('users.show', ['username' => $bookmark->user->username]) }}">
                                                ({{ $bookmark->user->username }})
                                            </a>
                                        @endif
                                    </span>
                                @else
                                    <span class="badge-extra text-bold">
                                        <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By
                                        <a href="{{ route('users.show', ['username' => $bookmark->user->username]) }}">
                                            {{ $bookmark->user->username }}
                                        </a>
                                    </span>
                                @endif

                                <span class="badge-extra text-bold text-pink">
                                    <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="Thanks Given"></i>
                                    {{ $bookmark->thanks()->count() }}
                                </span>
                                        
                                <span class="badge-extra text-bold text-green">
                                    <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="Comments Left"></i>
                                    {{ $bookmark->comments()->count() }}
                                </span>

                                @if ($bookmark->internal == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' data-original-title='Internal Release' style="color: #baaf92;"></i> Internal
                                    </span>
                                @endif

                                @if ($bookmark->stream == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' data-original-title='Stream Optimized'></i> Stream Optimized
                                    </span>
                                @endif

                                @if ($bookmark->featured == 0)
                                    @if ($bookmark->doubleup == 1)
                                        <span class='badge-extra text-bold'>
                                            <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' data-original-title='Double upload'></i> Double Upload
                                        </span>
                                    @endif
                                    @if ($bookmark->free == 1)
                                        <span class='badge-extra text-bold'>
                                            <i class='{{ config("other.font-awesome") }} fa-star text-gold' data-toggle='tooltip' data-original-title='100% Free'></i> 100% Free
                                        </span>
                                    @endif
                                @endif

                                @if ($personal_freeleech)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-id-badge text-orange' data-toggle='tooltip' data-original-title='Personal FL'></i> Personal FL
                                    </span>
                                @endif

                                @php $freeleech_token = \App\Models\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $bookmark->id)->first(); @endphp
                                @if ($freeleech_token)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-star text-bold' data-toggle='tooltip' data-original-title='Freeleech Token'></i> Freeleech Token
                                    </span>
                                @endif

                                @if ($bookmark->featured == 1)
                                    <span class='badge-extra text-bold' style='background-image:url(/img/sparkels.gif);'>
                                        <i class='{{ config("other.font-awesome") }} fa-certificate text-pink' data-toggle='tooltip' data-original-title='Featured Torrent'></i> Featured
                                    </span>
                                @endif

                                @if ($user->group->is_freeleech == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-trophy text-purple' data-toggle='tooltip' data-original-title='Special FL'></i> Special FL
                                    </span>
                                @endif

                                @if (config('other.freeleech') == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-globe text-blue' data-toggle='tooltip' data-original-title='Global FreeLeech'></i> Global FreeLeech
                                    </span>
                                @endif

                                @if (config('other.doubleup') == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-globe text-green' data-toggle='tooltip' data-original-title='Double Upload'></i> Global Double Upload
                                    </span>
                                @endif

                                @if ($bookmark->leechers >= 5)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' data-original-title='Hot!'></i> Hot
                                    </span>
                                @endif

                                @if ($bookmark->sticky == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' data-original-title='Sticky!'></i> Sticky
                                    </span>
                                @endif

                                @if ($user->updated_at->getTimestamp() < $bookmark->created_at->getTimestamp())
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' data-original-title='NEW!'></i> NEW
                                    </span>
                                @endif

                                @if ($bookmark->highspeed == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' data-original-title='High Speeds!'></i> High Speeds
                                    </span>
                                @endif

                                @if ($bookmark->sd == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' data-original-title='SD Content!'></i> SD Content
                                    </span>
                                @endif
                            </td>

                            <td>
                                <time>
                                    {{ $bookmark->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                <span class='badge-extra text-blue text-bold'>
                                    {{ $bookmark->getSize() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $bookmark->id]) }}">
                                    <span class='badge-extra text-green text-bold'>
                                        {{ $bookmark->seeders }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $bookmark->id]) }}">
                                    <span class='badge-extra text-red text-bold'>
                                        {{ $bookmark->leechers }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('history', ['id' => $bookmark->id]) }}">
                                    <span class='badge-extra text-orange text-bold'>
                                        {{ $bookmark->times_completed }} @lang('common.times')
                                    </span>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('bookmarks.destroy', ['id' => $bookmark->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xxs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i> @lang('torrent.delete-bookmark')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                <div class="text-center">
                    {{ $bookmarks->links() }}
                </div>
                @if (count($bookmarks) <= 0)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-blue"><i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i> @lang('torrent.no-bookmarks')</h1>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
