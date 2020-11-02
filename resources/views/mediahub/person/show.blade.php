@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.persons.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Persons</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.persons.show', ['id' => $details->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $details->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">

        <div class="block">
        <div class="header gradient silver">
            <div class="inner_content">
                <h1 style=" margin: 2px;">{{ $details->name }}</h1>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-sm-12 movie-list" style=" margin: 0;">
                <div class="row">

                    <section class="col-sm-3">
                        <img src="{{ $details->still }}" alt="{{ $details->name }}" class="img-responsive thumb">
                    </section>

                    <section class="col-sm-9">

                        <p class="actor-bio shortened">{{ $details->biography }}</p>

                        <br>

                        <i class="fa fa-book text-green" aria-hidden="true"></i> Read Full Bio On Wikipedia:
                        <a target="_blank" href="https://en.wikipedia.org/wiki/{{ $details->name }}">{{ $details->name }} Wiki</a>

                        <hr>

                        <dl class="dl-horizontal">
                            <dt><i class="fa fa-heart text-green" aria-hidden="true"></i> Born: </dt>
                            <dd>
                                {{ $details->birthday ?? '' }}
                                In {{ $details->place_of_birth ?? '' }}
                            </dd>
                            <br>
                            <dt><i class="fa fa-film text-green" aria-hidden="true"></i> Movie Credits: </dt>
                            <dd>{{ $credits->movie->count() ?? '0' }}</dd>
                            <dt><i class="fa fa-step-backward text-green" aria-hidden="true"></i> First Appeared: </dt>
                            <dd>In <a href="{{ route('mediahub.shows.show', ['id' => $credits->movie->first()->id ?? '0']) }}">{{ $credits->movie->first()->title ?? 'N/A'}}</a></dd>
                            <dt><i class="fa fa-step-forward text-green" aria-hidden="true"></i> Latest Project: </dt>
                            <dd>Last in <a href="{{ route('mediahub.shows.show', ['id' => $credits->movie->last()->id ?? '0']) }}">{{ $credits->movie->last()->title ?? 'N/A' }} </a></dd>
                            <br>
                            <dt><i class="fa fa-tv-retro text-green" aria-hidden="true"></i> TV Credits: </dt>
                            <dd>{{ $credits->tv->count() ?? '0' }}</dd>
                            <dt><i class="fa fa-step-backward text-green" aria-hidden="true"></i> First Appeared: </dt>
                            <dd>In <a href="{{ route('mediahub.shows.show', ['id' => $credits->tv->first()->id ?? '0']) }}">{{ $credits->tv->first()->name ?? 'N/A'}}</a></dd>
                            <dt><i class="fa fa-step-forward text-green" aria-hidden="true"></i> Latest Project: </dt>
                            <dd>Last in <a href="{{ route('mediahub.shows.show', ['id' => $credits->tv->last()->id ?? '0']) }}">{{ $credits->tv->last()->name ?? 'N/A' }} </a></dd>
                        </dl>

                    </section>
                </div>
            </div>
        </div>
        </div>

        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1 style=" margin: 2px;">Movies</h1>
                </div>
            </div>
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->movie))
                    @if (count($credits->movie) <= 0) <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-blue">
                                <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>No Data Found!</h1>
                        </div>
                    </div>
                    @endif
                    @foreach($credits->movie as $movie)
                        <tr>
                            <td class="col-sm-1">
                                @if($movie->poster)
                                    <img src="{{ $movie->poster }}" width="100" height="100" alt="{{ $movie->name }}" class="img-responsive">
                                @else
                                    <img src="/img/no-poster.png" width="100" height="100" alt="{{ $movie->name }}" class="img-responsive">
                                @endif
                            </td>
                            <td class="col-sm-5">
                                <i class="fa fa-film text-purple" aria-hidden="true"></i> <strong>Movie</strong><br>
                                <i class="fa fa-eye text-green" aria-hidden="true"></i> <a href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}">{{ $movie->title }}</a><br>
                                <i class="fa fa-tags text-red" aria-hidden="true"></i>
                                <strong>
                                    @if ($movie->genres)
                                        @foreach ($movie->genres as $genre)
                                            {{ $movie->name }}
                                        @endforeach
                                    @endif
                                </strong>
                                <br>
                                <i class="fa fa-calendar text-blue" aria-hidden="true"></i> <strong>Release date: </strong>{{ $movie->release_date }}<br>
                            </td>
                            <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i> <strong>Plot: </strong>
                                {{ $movie->overview }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1 style=" margin: 2px;">TV</h1>
                </div>
            </div>
            <table class="table table-striped clearfix">
                <tbody>
                @if(! empty($credits->tv))
                    @if (count($credits->tv) <= 0)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-blue">
                                <i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>No Data Found!</h1>
                        </div>
                    </div>
                    @endif
                @foreach($credits->tv as $show)
                <tr>
                    <td class="col-sm-1">
                        @if($show->poster)
                            <img src="{{ $show->poster }}" width="100" height="100" alt="{{ $show->name }}" class="img-responsive">
                        @else
                            <img src="/img/no-poster.png" width="100" height="100" alt="{{ $show->name }}" class="img-responsive">
                        @endif
                    </td>
                    <td class="col-sm-5">
                        <i class="fa fa-tv-retro text-purple" aria-hidden="true"></i> <strong>Show</strong><br>
                        <i class="fa fa-eye text-green" aria-hidden="true"></i> <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}</a><br>
                        <i class="fa fa-tags text-red" aria-hidden="true"></i>
                        <strong>
                            @if ($show->genres)
                                @foreach ($show->genres as $genre)
                                    {{ $genre->name }}
                                @endforeach
                            @endif
                        </strong>
                        <br>
                        <i class="fa fa-calendar text-blue" aria-hidden="true"></i> <strong>Release Date: </strong>{{ $show->first_air_date }}<br>
                    </td>
                    <td class="col-xs-pull-6"><i class="fa fa-book text-gold" aria-hidden="true"></i> <strong>Plot: </strong>
                        {{ $show->overview }}
                    </td>
                </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection