@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.torrent-categories')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('torrent.categories')</h2>
        <a href="{{ route('staff.categories.create') }}" class="btn btn-primary">
            @lang('common.add')
            @lang(trans_choice('common.a-an-art',false))
            @lang('torrent.category')
        </a>
    
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@lang('common.position')</th>
                        <th>@lang('common.name')</th>
                        <th>@lang('common.icon')</th>
                        <th>@lang('common.image')</th>
                        <th>Movie Meta</th>
                        <th>TV Meta</th>
                        <th>Game Meta</th>
                        <th>Music Meta</th>
                        <th>No Meta</th>
                        <th>@lang('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                {{ $category->position }}
                            </td>
                            <td>
                                <a
                                    href="{{ route('staff.categories.edit', ['id' => $category->id]) }}">{{ $category->name }}</a>
                            </td>
                            <td>
                                <i class="{{ $category->icon }}" aria-hidden="true"></i>
                            </td>
                            <td>
                                @if ($category->image != null)
                                    <img alt="{{ $category->name }}" src="{{ url('files/img/' . $category->image) }}">
                                @else
                                    <span>N/A</span>
                                @endif
                            </td>
                            <td>
                                @if ($category->movie_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->tv_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->game_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->music_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->no_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('staff.categories.destroy', ['id' => $category->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('staff.categories.edit', ['id' => $category->id]) }}"
                                        class="btn btn-warning">
                                        @lang('common.edit')
                                    </a>
                                    <button type="submit" class="btn btn-danger">@lang('common.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
