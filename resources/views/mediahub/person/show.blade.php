@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.persons.index') }}" class="breadcrumb__link">
            {{ __('mediahub.persons') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $person->name }}
    </li>
@endsection

@section('main')
    @livewire('person-credit', ['person' => $person])
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $person->name }}</h2>
        <img
            src="{{ isset($person->still) ? tmdb_image('cast_big', $person->still) : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $person->name }}"
            style="max-width: 100%;"
        >
        <div class="panel__body">{{ $person->biography ?? 'No biography' }}</div>
        <dl class="key-value">
            <dt>{{ __('mediahub.born') }}</dt>
            <dd>{{ $person->birthday ?? __('common.unknown') }}</dd>
            <dt>Place of Birth</dt>
            <dd>{{ $person->place_of_birth ?? __('common.unknown') }}</dd>
            <dt>{{ __('mediahub.movie-credits') }}</dt>
            <dd>{{ $person->movie->count() ?? '0' }}</dd>
            <dt>{{ __('mediahub.first-seen') }} </dt>
            <dd>
                <a href="{{ route('torrents.index', ['view' => 'group', 'tmdb' => $person->movie->first()->id ?? '0', 'categories' => $movieCategoryIds]) }}">
                    {{ $person->movie->first()->title ?? 'N/A'}}
                </a>
            </dd>
            <dt>{{ __('mediahub.latest-project') }}</dt>
            <dd>
                <a href="{{ route('torrents.index', ['view' => 'group', 'tmdb' => $person->movie->last()->id ?? '0', 'categories' => $movieCategoryIds]) }}">
                    {{ $person->movie->last()->title ?? 'N/A' }}
                </a>
            </dd>
            <dt>{{ __('mediahub.tv-credits') }}</dt>
            <dd>{{ $person->tv->count() ?? '0' }}</dd>
            <dt>{{ __('mediahub.first-seen') }}</dt>
            <dd>
                In
                <a href="{{ route('torrents.index', ['view' => 'group', 'tmdb' => $person->tv->first()->id ?? '0', 'categories' => $tvCategoryIds]) }}">
                    {{ $person->tv->first()->name ?? 'N/A'}}
                </a>
            </dd>
            <dt>{{ __('mediahub.latest-project') }}</dt>
            <dd>
                Last in
                <a href="{{ route('torrents.index', ['view' => 'group', 'tmdb' => $person->tv->last()->id ?? '0', 'categories' => $tvCategoryIds]) }}">
                    {{ $person->tv->last()->name ?? 'N/A' }}
                </a>
            </dd>
        </dl>
    </section>
@endsection
