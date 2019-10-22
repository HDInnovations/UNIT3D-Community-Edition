@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.uploads') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_uploads', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.uploads')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        @if (!auth()->user()->isAllowed($user,'torrent','show_upload'))
            <div class="container pl-0 text-center">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>@lang('user.private-profile')
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">@lang('user.not-authorized')</p>
                    </div>
                </div>
            </div>
        @else
        <div class="block">
            @include('user.buttons.public')
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        {{ $user->username }} @lang('user.uploads')
                    </h1>
                </div>
            </div>
                <div view="uploads">
                    <!-- Uploads -->
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                            <th>@lang('torrent.name')</th>
                            <th>@lang('torrent.category')</th>
                            <th>@lang('torrent.size')</th>
                            <th>@lang('torrent.seeders')</th>
                            <th>@lang('torrent.leechers')</th>
                            <th>@lang('torrent.completed')</th>
                            <th>@lang('torrent.created_at')</th>
                            </thead>
                            <tbody>
                            @foreach ($uploads as $upload)
                                <tr>
                                    <td>
                                        <a class="view-torrent" href="{{ route('torrent', ['slug' => $upload->slug, 'id' => $upload->id]) }}">
                                            {{ $upload->name }}
                                        </a>
                                        <div class="pull-right">
                                            <a href="{{ route('download', ['slug' => $upload->slug, 'id' => $upload->id]) }}">
                                                <button class="btn btn-primary btn-circle" type="button"><i
                                                            class="{{ config('other.font-awesome') }} fa-download"></i></button>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.show', ['id' => $upload->category->id]) }}">{{ $upload->category->name }}</a>
                                    </td>
                                    <td>
                                        <span class="badge-extra text-blue text-bold"> {{ $upload->getSize() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-extra text-green text-bold"> {{ $upload->seeders }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-extra text-red text-bold"> {{ $upload->leechers }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-extra text-orange text-bold"> {{ $upload->times_completed }} @lang('common.times')</span>
                                    </td>
                                    <td>{{ ($upload->created_at ? $upload->created_at->diffForHumans() : 'N/A') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $uploads->links() }}
                        </div>
                    </div>
                </div>
        </div>
        @endif
    </div>
@endsection
