@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('rss.rss')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <div class="button-holder">
                <div class="button-left">
                    <a href="{{ route('rss.create') }}" class="btn btn-sm btn-primary">Create RSS Feed</a>
                </div>
                <div class="button-right">

                </div>
            </div>
            <div class="header gradient orange">
                <div class="inner_content">
                    <h1>{{ trans('rss.rss') }} {{ trans('rss.feeds') }}</h1>
                </div>
            </div>
            <div class="container-fluid p-0 some-padding">
                        <div class="block">
                            <ul class="nav nav-tabs" id="basetabs" role="tablist">
                                <li id="public-tab" class="active"><a href="#public" data-toggle="tab">Public</a></li>
                                <li id="private-tab"><a href="#private" data-toggle="tab">Private</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="public">
                                    <table class="table table-hover rss-table middle-align">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Categories</th>
                                            <th>Types</th>
                                            <th>Genres</th>
                                            <th>Discounts</th>
                                            <th>Special</th>
                                            <th>Health</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($public_rss as $rss)
                                            <tr>
                                                <td><a href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => auth()->user()->rsskey ]) }}" target="_blank">{{ $rss->name }}</a></td>
                                                @if($rss->object_torrent)
                                                    <td>@if ($rss->object_torrent->categories)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->types)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->genres)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->freeleech || $rss->object_torrent->doubleupload || $rss->object_torrent->featured)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->stream || $rss->object_torrent->highspeed || $rss->object_torrent->sd || $rss->object_torrent->internal)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->alive || $rss->object_torrent->dying || $rss->object_torrent->dead)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                @else
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="private">
                                    <table class="table table-hover rss-table middle-align">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Categories</th>
                                            <th>Types</th>
                                            <th>Genres</th>
                                            <th>Discounts</th>
                                            <th>Special</th>
                                            <th>Health</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($private_rss as $rss)
                                            <tr>
                                                <td><a href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => auth()->user()->rsskey ]) }}" target="_blank">{{ $rss->name }}</a></td>
                                                @if($rss->object_torrent)
                                                    <td>@if ($rss->object_torrent->categories)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->types)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->genres)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->freeleech || $rss->object_torrent->doubleupload || $rss->object_torrent->featured)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->stream || $rss->object_torrent->highspeed || $rss->object_torrent->sd || $rss->object_torrent->internal)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                    <td>@if ($rss->object_torrent->alive || $rss->object_torrent->dying || $rss->object_torrent->dead)<i class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                                                class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                                @else
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                                @endif
                                                @if(auth()->user()->id == $rss->user_id)
                                                    <td>
                                                        <form action="{{ route('rss.destroy', ['id' => $rss->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="{{ route('rss.edit', ['id' => $rss->id]) }}" class="btn btn-warning">@lang('common.edit')</a>
                                                            <button type="submit" class="btn btn-danger">@lang('common.delete')</button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        $(window).on("load", function() { loadTab(); });
        function loadTab() {
            if(window.location.hash && window.location.hash == "#private") {
                $('#basetabs a[href="#private"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#public") {
                $('#basetabs a[href="#public"]').tab('show');
            }
        }
    </script>
@endsection