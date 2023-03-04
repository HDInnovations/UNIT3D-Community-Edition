@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('outbox') }}" class="breadcrumb__link">
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('pm.outbox') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('page', 'page__pm--outbox')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('pm.outbox') }}</h2>
            <div class="panel__actions">
                <form
                    class="panel__action"
                    action="{{ route('searchPMOutbox') }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="search"
                            class="form__text"
                            name="subject"
                            required
                        />
                        <label class="form__label form__label--floating" for="search">
                            {{ __('pm.search') }}
                        </label>
                    </p>
                </form>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('pm.to') }}</th>
                        <th>{{ __('pm.subject') }}</th>
                        <th>{{ __('pm.sent-at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pms as $pm)
                        <tr>
                            <td>
                                <x-user_tag :user="$pm->receiver" :anon="false" />
                            </td>
                            <td>
                                <a href="{{ route('message', ['id' => $pm->id]) }}">
                                    {{ $pm->subject }}
                                </a>
                            </td>
                            <td>
                                <time datetime="{{ $pm->created_at }}" title="{{ $pm->created_at }}">
                                    {{ $pm->created_at->diffForHumans() }}
                                </time>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $pms->links('partials.pagination') }}
    </section>
@endsection
