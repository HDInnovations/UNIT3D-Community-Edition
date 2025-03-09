@extends('layout.with-main')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('event.events') }}
    </li>
@endsection

@section('page', 'page__event--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('event.events') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('event.starts-at') }}</th>
                        <th>{{ __('event.ends-at') }}</th>
                        <th>{{ __('common.active') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.show', ['event' => $event]) }}">
                                    {{ $event->name }}
                                </a>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $event->starts_at }}"
                                    title="{{ $event->starts_at }}"
                                >
                                    {{ $event->starts_at->format('Y-m-d') }}
                                </time>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $event->ends_at }}"
                                    title="{{ $event->ends_at }}"
                                >
                                    {{ $event->ends_at->format('Y-m-d') }}
                                </time>
                            </td>
                            <td>
                                @if ($event->active)
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-check text-green"
                                    ></i>
                                @else
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-times text-red"
                                    ></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
