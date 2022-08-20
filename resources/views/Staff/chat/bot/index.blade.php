@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.bots') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.statuses.index') }}">
            {{ __('staff.statuses') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.rooms.index') }}">
            {{ __('staff.rooms') }}
        </a>
    </li>
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('staff.bots.index') }}">
            {{ __('staff.bots') }}
        </a>
    </li>
@endsection

@section('page', 'page__chat-bot--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.bots') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
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
                            <td>
                                <a href="{{ route('staff.bots.edit', ['id' => $bot->id]) }}">
                                    {{ $bot->name }}
                                </a>
                            </td>
                            <td>{{ $bot->position }}</td>
                            <td>
                                <img
                                    class="chat-bots__icon"
                                    src="/vendor/joypixels/png/64/{{ $bot->emoji }}.png"
                                    alt="emoji"
                                >
                            </td>
                            <td>{{ $bot->command }}</td>
                            <td>
                                @if ($bot->active)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                <div class="data-table__actions">
                                    @if(! $bot->is_systembot)
                                        @if($bot->active)
                                            <li class="data-table__action">
                                                <form
                                                    method="POST"
                                                    action="{{ route('staff.bots.disable', ['id' => $bot->id]) }}"
                                                >
                                                    @csrf
                                                    <button class="form__button form__button--text">
                                                        {{ __('common.disable') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li class="data-table__action">
                                                <form
                                                    method="POST"
                                                    action="{{ route('staff.bots.enable', ['id' => $bot->id]) }}"
                                                >
                                                    @csrf
                                                    <button class="form__button form__button--text">
                                                        {{ __('common.enable') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    @endif
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.bots.edit', ['id' => $bot->id]) }}"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            class="data-table__action"
                                            action="{{ route('staff.bots.destroy', ['id' => $bot->id]) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            @if(!$bot->is_protected)
                                                <button class="form__button form__button--text">
                                                    {{ __('common.delete') }}
                                                </button>
                                            @endif
                                        </form>
                                    </li>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
