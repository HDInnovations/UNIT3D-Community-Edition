@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.rss.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('rss.rss') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="block">
            <h2>{{ __('rss.public') }} {{ __('rss.rss-feed') }}</h2>
            <a href="{{ route('staff.rss.create') }}"
               class="btn btn-primary">{{ __('common.create') }} {{ __('rss.rss-feed') }}</a>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.position') }}</th>
                        <th>{{ __('common.categories') }}</th>
                        <th>{{ __('common.types') }}</th>
                        <th>{{ __('common.resolutions') }}</th>
                        <th>{{ __('common.genres') }}</th>
                        <th>{{ __('torrent.discounts') }}</th>
                        <th>{{ __('common.special') }}</th>
                        <th>{{ __('torrent.health') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($public_rss as $rss)
                        <tr>
                            <td>
                                <a href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => auth()->user()->rsskey]) }}"
                                   target="_blank">{{ $rss->name }}</a></td>
                            <td>{{ $rss->position }}</td>
                            @if($rss->object_torrent)
                                <td>@if ($rss->object_torrent->categories)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->types)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->resolutions)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->genres)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->freeleech || $rss->object_torrent->doubleupload ||
                                            $rss->object_torrent->featured)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->stream || $rss->object_torrent->highspeed ||
                                            $rss->object_torrent->sd || $rss->object_torrent->internal ||
                                            $rss->object_torrent->bookmark)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>@if ($rss->object_torrent->alive || $rss->object_torrent->dying ||
                                            $rss->object_torrent->dead)<i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                            @else
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                            @endif
                            <td>
                                <form action="{{ route('staff.rss.destroy', ['id' => $rss->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('staff.rss.edit', ['id' => $rss->id]) }}"
                                       class="btn btn-warning">{{ __('common.edit') }}</a>
                                    <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
