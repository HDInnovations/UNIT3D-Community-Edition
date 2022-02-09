@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.bots.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.bots') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="block">
            <h2>{{ __('staff.bots') }}</h2>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.position') }}</th>
                        <th>{{ __('common.icon') }}</th>
                        <th>{{ __('common.command') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bots as $bot)
                        <tr>
                            <td>{{ $bot->name }}</td>
                            <td>{{ $bot->position }}</td>
                            <td><img src="/vendor/joypixels/png/64/{{ $bot->emoji }}.png" alt="emoji"
                                     style="max-width: 24px;"/></td>
                            <td>{{ $bot->command }}</td>
                            <td>@if ($bot->active)<i
                                        class="{{ config('other.font-awesome') }} fa-check text-green"></i>@else<i
                                        class="{{ config('other.font-awesome') }} fa-times text-red"></i>@endif</td>
                            <td>
                                <form action="{{ route('staff.bots.destroy', ['id' => $bot->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('staff.bots.edit', ['id' => $bot->id]) }}"
                                       class="btn btn-warning">{{ __('common.edit') }}</a>
                                    @if($bot->is_protected)

                                    @else
                                        <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                                    @endif
                                </form>
                                @if($bot->is_systembot)

                                @else
                                    @if($bot->active)
                                        <form role="form" method="POST"
                                              action="{{ route('staff.bots.disable', ['id' => $bot->id]) }}"
                                              style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-warning">
                                                <i class='{{ config('other.font-awesome') }} fa-times-circle'></i> {{ __('common.disable') }}
                                            </button>
                                        </form>
                                    @else
                                        <form role="form" method="POST"
                                              action="{{ route('staff.bots.enable', ['id' => $bot->id]) }}"
                                              style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-success">
                                                <i class='{{ config('other.font-awesome') }} fa-check-circle'></i> {{ __('common.enable') }}
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
