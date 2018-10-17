@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.bookmarks') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <div class="header gradient orange">
                <div class="inner_content">
                    <h1>{{ trans('common.my') }} {{ strtolower(trans('torrent.bookmarks')) }}</h1>
                </div>
            </div>
            <div class="table-responsive">
                <div class="pull-right"></div>
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-1">Category/Type</th>
                        <th>{{ trans('common.name') }}</th>
                        <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-cogs"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($bookmarks) == 0)
                        <tr>
                            <td>
                                <p>{{ trans('torrent.no-bookmarks') }}</p>
                            </td>
                        </tr>
                    @else
                    @foreach ($bookmarks as $bookmark)
                        <tr>
                            <td>
                                <a href="{{ route('category', ['slug' => $bookmark->torrent->category->slug, 'id' => $bookmark->torrent->category->id]) }}">
                                    <div class="text-center">
                                        <i class="{{ $bookmark->torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                           data-original-title="{{ $bookmark->torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                           style="padding-bottom: 6px;"></i>
                                    </div>
                                </a>
                                <div class="text-center">
                                    <span class="label label-success" data-toggle="tooltip" data-original-title="Type">
                                        {{ $bookmark->torrent->type }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <a class="view-torrent" href="{{ route('torrent', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                    {{ $bookmark->torrent->name }}
                                </a>
                                @if (config('torrent.download_check_page') == 1)
                                    <a href="{{ route('download_check', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('download', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @endif
                                
                                @php $history = \App\History::where('user_id', '=', $user->id)->where('info_hash', '=', $bookmark->torrent->info_hash)->first(); @endphp
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
                                            <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i>
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
                                
                                @if ($bookmark->torrent->anon == 1)
                                    <span class="badge-extra text-bold">
                                        <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By ANONYMOUS USER
                                        @if ($user->id == $bookmark->torrent->user->id || $user->group->is_modo)
                                            <a href="{{ route('profile', ['username' => $bookmark->torrent->user->username, 'id' => $bookmark->torrent->user->id]) }}">
                                                ({{ $bookmark->torrent->user->username }})
                                            </a>
                                        @endif
                                    </span>
                                @else
                                    <span class="badge-extra text-bold">
                                        <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By
                                        <a href="{{ route('profile', ['username' => $bookmark->torrent->user->username, 'id' => $bookmark->torrent->user->id]) }}">
                                            {{ $bookmark->torrent->user->username }}
                                        </a>
                                    </span>
                                @endif

                                <span class="badge-extra text-bold text-pink">
                                    <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="Thanks Given"></i>
                                    {{ $bookmark->torrent->thanks()->count() }}
                                </span>
                                        
                                <span class="badge-extra text-bold text-green">
                                    <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="Comments Left"></i>
                                    {{ $bookmark->torrent->comments()->count() }}
                                </span>

                                @if ($bookmark->torrent->internal == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' data-original-title='Internal Release' style="color: #BAAF92"></i> Internal
                                    </span>
                                @endif

                                @if ($bookmark->torrent->stream == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' data-original-title='Stream Optimized'></i> Stream Optimized
                                    </span>
                                @endif

                                @if ($bookmark->torrent->featured == 0)
                                    @if ($bookmark->torrent->doubleup == 1)
                                        <span class='badge-extra text-bold'>
                                            <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' data-original-title='Double upload'></i> Double Upload
                                        </span>
                                    @endif
                                    @if ($bookmark->torrent->free == 1)
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

                                @php $freeleech_token = \App\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $bookmark->torrent->id)->first(); @endphp
                                @if ($freeleech_token)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-viacoin text-bold' data-toggle='tooltip' data-original-title='Freeleech Token'></i> Freeleech Token
                                    </span>
                                @endif

                                @if ($bookmark->torrent->featured == 1)
                                    <span class='badge-extra text-bold' style='background-image:url(https://i.imgur.com/F0UCb7A.gif);'>
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

                                @if ($bookmark->torrent->leechers >= 5)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' data-original-title='Hot!'></i> Hot
                                    </span>
                                @endif

                                @if ($bookmark->torrent->sticky == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' data-original-title='Sticky!'></i> Sticky
                                    </span>
                                @endif

                                @if ($user->updated_at->getTimestamp() < $bookmark->torrent->created_at->getTimestamp())
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' data-original-title='NEW!'></i> NEW
                                    </span>
                                @endif

                                @if ($bookmark->torrent->highspeed == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' data-original-title='High Speeds!'></i> High Speeds
                                    </span>
                                @endif

                                @if ($bookmark->torrent->sd == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' data-original-title='SD Content!'></i> SD Content
                                    </span>
                                @endif
                            </td>

                            <td>
                                <time>
                                    {{ $bookmark->torrent->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                <span class='badge-extra text-blue text-bold'>
                                    {{ $bookmark->torrent->getSize() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('history', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                    <span class='badge-extra text-orange text-bold'>
                                        {{ $bookmark->torrent->times_completed }} {{ trans('common.times') }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                    <span class='badge-extra text-green text-bold'>
                                        {{ $bookmark->torrent->seeders }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['slug' => $bookmark->torrent->slug, 'id' => $bookmark->torrent->id]) }}">
                                    <span class='badge-extra text-red text-bold'>
                                        {{ $bookmark->torrent->leechers }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('unbookmark', ['id' => $bookmark->torrent->id]) }}">
                                    <button type="button" id="{{ $bookmark->torrent->id }}"
                                            class="btn btn-xxs btn-danger btn-delete-wishlist" data-toggle="tooltip"
                                            data-original-title="{{ trans('torrent.delete-bookmark') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-times"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $bookmarks->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
