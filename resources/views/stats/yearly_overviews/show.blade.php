@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('yearly_overviews.index') }}" class="breadcrumb__link">
            {{ __('common.overview') }}
        </a>
    </li>
    <li class="breadcrumb--active">{{ $year }}</li>
@endsection

@section('nav-tabs')
    @for ($i = $birthYear; $i < now()->year; $i++)
        <li class="{{ $i === $year ? 'nav-tab--active' : 'nav-tabV2' }}">
            <a
                class="{{ $i === $year ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                href="{{ route('yearly_overviews.show', ['year' => $i]) }}"
            >
                {{ $i }}
            </a>
        </li>
    @endfor
@endsection

@section('page', 'page__stats--overview')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Yearly Overview</h2>
        <div class="panel__body">
            <div class="overview__opening">
                <h1 class="overview__opening-heading">That's A Wrap!</h1>
                <h2 class="overview__opening-subheading">{{ $year }}</h2>
                <p class="overview__opening-text">
                    Another strong year here at {{ config('app.name') }}. To every user who made a
                    contribution big or small please accept our sincere thanks. Now, without further
                    ado, here's the best and worst of the year!
                </p>
            </div>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Movies (Based on downloads count)</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($topMovies as $work)
                <figure class="top10-poster overview__poster">
                    <x-movie.poster
                        :movie="$work->movie"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">5 Worst Movies (Based on downloads count)</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($bottomMovies as $work)
                <figure class="top10-poster overview__poster">
                    <x-movie.poster
                        :movie="$work->movie"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 TV Shows (Based on downloads count)</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($topTv as $work)
                <figure class="top10-poster overview__poster">
                    <x-tv.poster
                        :tv="$work->tv"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">5 Worst TV Shows (Based on downloads count)</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($bottomTv as $work)
                <figure class="top10-poster overview__poster">
                    <x-tv.poster
                        :tv="$work->tv"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of torrent uploads made)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($uploaders as $uploader)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$uploader->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $uploader->value }} {{ __('user.uploads') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($uploader->user->image === null ? 'img/profile.png' : 'files/img/' . $uploader->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of torrent requests made)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($requesters as $requester)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$requester->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $requester->value }} {{ __('request.requests') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($requester->user->image === null ? 'img/profile.png' : 'files/img/' . $requester->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of torrent requests filled)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($fillers as $filler)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$filler->filler" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $filler->value }} {{ __('notification.request-fills') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($filler->filler->image === null ? 'img/profile.png' : 'files/img/' . $filler->filler->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of comments made)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($commenters as $commenter)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$commenter->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $commenter->value }} {{ __('user.comments') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($commenter->user->image === null ? 'img/profile.png' : 'files/img/' . $commenter->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of posts made)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($posters as $poster)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$poster->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $poster->value }} {{ __('common.posts') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($poster->user->image === null ? 'img/profile.png' : 'files/img/' . $poster->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Top 10 Users (Based on number of thanks given)</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($thankers as $thanker)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$thanker->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $thanker->value }} {{ __('torrent.thanks') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($thanker->user->image === null ? 'img/profile.png' : 'files/img/' . $thanker->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Overall</h2>
        <dl class="key-value">
            <dt>New users this year</dt>
            <dd>{{ $newUsers }}</dd>
            <dt>Movies uploaded this year</dt>
            <dd>{{ $movieUploads }}</dd>
            <dt>TV Shows uploaded this year</dt>
            <dd>{{ $tvUploads }}</dd>
            <dt>Total torrents uploaded this year</dt>
            <dd>{{ $totalUploads }}</dd>
            <dt>Total torrents downloaded this year</dt>
            <dd>{{ $totalDownloads }}</dd>
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Closing Remarks</h2>
        <div class="panel__body overview__closing">
            <h3 class="overview__closing-heading">Thank You!</h3>
            <h4 class="overview__closing-subheading">
                For a wonderful {{ $year }} at {{ config('app.name') }}
            </h4>
            <span class="overview__closing-thanks">Special thanks from,</span>
            @foreach ($staffers as $group)
                <ul class="overview__staff-list">
                    @foreach ($group->users as $user)
                        <li class="overview__staff-list-item">
                            <x-user_tag :user="$user" :anon="false" />
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </section>
@endsection
