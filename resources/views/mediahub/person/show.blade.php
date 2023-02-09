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
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.movies') }} ({{ $person->movie->count() }})</h2>
        <div class="panel__body">
            <table class="table table-striped clearfix">
                <tbody>
                    @forelse($person->movie as $movie)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($movie->poster) ? tmdb_image('poster_small', $movie->poster) : 'https://via.placeholder.com/90x135' }}"
                                    alt="{{ $movie->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}">{{ $movie->title }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($movie->genres)
                                        @foreach ($movie->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $movie->release_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $movie->overview }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">{{ __('mediahub.no-data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.shows') }} ({{ $person->tv->count() }})</h2>
        <div class="panel__body">
            <table class="table table-striped">
                <tbody>
                    @forelse($person->tv as $show)
                        <tr>
                            <td class="col-sm-1">
                                <img src="{{ isset($show->poster) ? tmdb_image('poster_small', $show->poster) : 'https://via.placeholder.com/90x135' }}"
                                    alt="{{ $show->name }}" class="img-responsive">
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a
                                        href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($show->genres)
                                        @foreach ($show->genres as $genre)
                                            {{ $genre->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.release-date') }} </strong>{{ $show->first_air_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i>
                                <strong>{{ __('mediahub.plot') }} </strong>
                                {{ $show->overview }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">{{ __('mediahub.no-data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $person->name }}</h2>
        <img
            src="{{ isset($person->still) ? tmdb_image('cast_big', $person->still) : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $person->name }}"
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
                <a href="{{ route('mediahub.movies.show', ['id' => $person->movie->first()->id ?? '0']) }}">
                    {{ $person->movie->first()->title ?? 'N/A'}}
                </a>
            </dd>
            <dt>{{ __('mediahub.latest-project') }}</dt>
            <dd>
                <a href="{{ route('mediahub.movies.show', ['id' => $person->movie->last()->id ?? '0']) }}">
                    {{ $person->movie->last()->title ?? 'N/A' }}
                </a>
            </dd>
            <dt>{{ __('mediahub.tv-credits') }}</dt>
            <dd>{{ $person->tv->count() ?? '0' }}</dd>
            <dt>{{ __('mediahub.first-seen') }}</dt>
            <dd>
                In
                <a href="{{ route('mediahub.shows.show', ['id' => $person->tv->first()->id ?? '0']) }}">
                    {{ $person->tv->first()->name ?? 'N/A'}}
                </a>
            </dd>
            <dt>{{ __('mediahub.latest-project') }}</dt>
            <dd>
                Last in
                <a href="{{ route('mediahub.shows.show', ['id' => $person->tv->last()->id ?? '0']) }}">
                    {{ $person->tv->last()->name ?? 'N/A' }}
                </a>
            </dd>
        </dl>
    </section>
@endsection
