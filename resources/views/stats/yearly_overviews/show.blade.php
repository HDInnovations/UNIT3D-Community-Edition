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

@section('page', 'page__stats--overview')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Yearly Overview</h2>
        <div class="panel__body">
            <div class="overview__welcome">
                <h1>Thats A Wrap!</h1>
                <h3>{{ $year }}</h3>
                <span>Another strong year here at {{ config('app.name') }}. To every user who made a contribution big or small please accept our sincere thanks. Now, without further ado, here's the best and worst of the year!</span>
            </div>
        </div>

        <h2 class="panel__heading">Top 10 Movies (Based on downloads count)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">5 Worst Movies (Based on downloads count)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 TV Shows (Based on downloads count)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">5 Worst TV Shows (Based on downloads count)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of torrent uploads made)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of torrent requests made)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of torrent requests filled)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of comments made)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of posts made)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Top 10 Users (Based on number of thanks given)</h2>
        <div class="panel__body">

        </div>

        <h2 class="panel__heading">Overall</h2>
        <div class="panel__body">
            <div class="stats">
                <div>
                    <h3>{{ $newUsers }}</h3>
                    <p>New users this year</p>
                </div>
                <div>
                    <h3>{{ $movieUploads }}</h3>
                    <p>Movies uploaded this year</p>
                </div>
                <div>
                    <h3>{{ $tvUploads }}</h3>
                    <p>TV Shows uploaded this year</p>
                </div>
                <div>
                    <h3>{{ $fanresUploads }}</h3>
                    <p>FANRES uploaded this year</p>
                </div>
                <div>
                    <h3>{{ $trailerUploads }}</h3>
                    <p>Trailers uploaded this year</p>
                </div>
                <div>
                    <h3>{{ $totalUploads }}</h3>
                    <p>Total torrents uploaded this year</p>
                </div>
                <div>
                    <h3>{{ $totalDownloads }}</h3>
                    <p>Total torrents downloaded this year</p>
                </div>
            </div>
        </div>

        <h2 class="panel__heading">Closing Remarks</h2>
        <div class="panel__body">
            <div class="overview__welcome">
                <h1>Thank You!</h1>
                <h3>For a wonderful {{ $year }} at {{ config('app.name') }}</h3>
                <span>Special thanks from,</span>
                @foreach ($staffers as $group)
                    <p>
                        @foreach ($group->users as $user)
                            {{ $user->username }},
                        @endforeach
                    </p>
                @endforeach
            </div>
        </div>

    </section>
@endsection
