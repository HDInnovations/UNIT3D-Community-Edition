@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.bots.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.bots')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="block">
            <h2>@lang('staff.bots')</h2>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('common.name')</th>
                            <th>@lang('common.position')</th>
                            <th>@lang('common.icon')</th>
                            <th>@lang('common.command')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bots as $bot)
                            <tr>
                                <td>{{ $bot->name }}</td>
                                <td>{{ $bot->position }}</td>
                                <td><img src="/img/joypixels/{{ $bot->emoji }}.png" alt="emoji" style="max-width: 24px;" /></td>
                                <td>{{ $bot->command }}</td>
                                <td>@if ($bot->active)<i
                                        class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                        class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                                <td>
                                    <form action="{{ route('staff.bots.destroy', ['id' => $bot->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('staff.bots.edit', ['id' => $bot->id]) }}"
                                            class="btn btn-warning">@lang('common.edit')</a>
                                        @if($bot->is_protected)
            
                                        @else
                                            <button type="submit" class="btn btn-danger">@lang('common.delete')</button>
                                        @endif
                                        @if($bot->is_systembot)
            
                                        @else
                                            @if($bot->active)
                                                <a href="{{ route('staff.bots.disable', ['id' => $bot->id]) }}"
                                                    class="btn btn-danger">@lang('common.disable')</a>
                                            @else
                                                <a href="{{ route('staff.bots.enable', ['id' => $bot->id]) }}"
                                                    class="btn btn-success">@lang('common.enable')</a>
                                            @endif
                                        @endif
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
