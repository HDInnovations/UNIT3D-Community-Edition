@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.tags.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Tags</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Tags (Genres)</h2>
        <a href="{{ route('staff.tags.create') }}" class="btn btn-primary">Add A Torrent Tag</a>
    
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@lang('common.name')</th>
                        <th>@lang('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>
                                <a href="{{ route('staff.tags.edit', ['id' => $tag->id]) }}">
                                    {{ $tag->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('staff.tags.edit', ['id' => $tag->id]) }}" class="btn btn-warning">
                                    @lang('common.edit')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
