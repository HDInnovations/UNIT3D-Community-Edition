@extends('layout.default')

@section('title')
    <title> - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.movies.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">TV Shows</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $movie->title }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="page-content">
                <div class="item_detail" style="background: url('https://image.tmdb.org/t/p/w780/ocUrMYbdjknu2TwzMHKT9PBBQRw.jpg') center/cover;">
                    <div class="item-overlay">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="movie-poster">
                                        <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2//8WUVHemHFH2ZIP6NWkwlHWsyrEL.jpg" alt="Bloodshot" class="img-responsive">
                                    </div>
                                </div>
                                <div class="col-xs-9">
                                    <div class="item-content">
                                        <h2>Bloodshot <span class="text-muted">(2020-02-20)</span></h2>
                                        <p class="genres">
                                            <span>Action <em>|</em></span>
                                            <span>Science Fiction <em>|</em></span>
                                        </p>
                                        <ul class="list-unstyled rating-list hidden-xs">
                                            <li class="rating-star">7</li>
                                            <li class="trailer hidden-xs hidden-sm">
                                                <button class="basic-button play-trailer" data-id="338762" data-url="">
                                                    <i class="mdi mdi-play"></i> Play Trailer
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                        <h3 class="hidden-sm hidden-xs">Overview</h3>
                                        <p class="hidden-sm hidden-xs">After he and his wife are murdered, marine Ray Garrison is resurrected by a team of scientists. Enhanced with nanotechnology, he becomes a superhuman, biotech killing machine—'Bloodshot'. As Ray first trains with fellow super-soldiers, he cannot recall anything from his former life. But when his memories flood back and he remembers the man that killed both him and his wife, he breaks out of the facility to get revenge, only to discover that there's more to the conspiracy than he thought.</p>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="cast-crew">
                                            <p><span>Directed By:</span>
                                                <a href="/person/1773871/dave-wilson">Dave Wilson</a>
                                            </p>
                                            <p><span>Star Cast:</span>
                                                <a href="/person/12835/vin-diesel">Vin Diesel <em>,</em></a>
                                                <a href="/person/1222992/eiza-gonzlez">Eiza González <em>,</em></a>
                                                <a href="/person/209326/sam-heughan">Sam Heughan <em>,</em></a>
                                                <a href="/person/20286/toby-kebbell">Toby Kebbell <em>,</em></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>

                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-7">
                            <div class="movie-main">

                                <div class="video-playlist">
                                    <h1><i class="mdi mdi-video"></i> Videos Playlist</h1>
                                    <div class="playlist slick-initialized slick-slider"><button class="slick-prev slick-arrow" aria-label="Previous" type="button" style="">Previous</button>
                                        <div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 5250px; transform: translate3d(-750px, 0px, 0px);"><div class="movie-trailer slick-slide slick-cloned" data-slick-index="-1" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/0R-qIOGyEcw" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" tabindex="0" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/F95Fk255I4M" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide" data-slick-index="1" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/vOUVVDWdXbo" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide" data-slick-index="2" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/0R-qIOGyEcw" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide slick-cloned" data-slick-index="3" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/F95Fk255I4M" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide slick-cloned" data-slick-index="4" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/vOUVVDWdXbo" frameborder="0" allowfullscreen=""></iframe>
                                                </div><div class="movie-trailer slick-slide slick-cloned" data-slick-index="5" aria-hidden="true" tabindex="-1" style="width: 750px;">
                                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/0R-qIOGyEcw" frameborder="0" allowfullscreen=""></iframe>
                                                </div></div></div>


                                        <button class="slick-next slick-arrow" aria-label="Next" type="button" style="">Next</button></div>
                                </div>
                                <span class="clearfix"></span>
                                <div class="text-center ad-code code2">
                                    <img src="/images/home-add.jpg" alt="ad" class="img-responsive">
                                </div>
                                <span class="clearfix"></span>
                                <div class="similar-list">
                                    <h1><i class="mdi mdi-content-copy"></i> Similar Collection</h1>
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="similar-card">
                                                <a href="/movie/9738/fantastic-four" class="explore">

                                                    <img src="https://image.tmdb.org/t/p/w300/sYNOCHiWA9UDUHlPvRWztSo5hZV.jpg" alt="Fantastic Four" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="similar-card">
                                                <a href="/movie/246655/x-men-apocalypse" class="explore">

                                                    <img src="https://image.tmdb.org/t/p/w300/zSouWWrySXshPCT4t3UKCQGayyo.jpg" alt="X-Men: Apocalypse" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="similar-card">
                                                <a href="/movie/141052/justice-league" class="explore">

                                                    <img src="https://image.tmdb.org/t/p/w300/eifGNCSDuxJeS1loAXil5bIGgvC.jpg" alt="Justice League" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="similar-card">
                                                <a href="/movie/166424/fantastic-four" class="explore">

                                                    <img src="https://image.tmdb.org/t/p/w300/g23cs30dCMiG4ldaoVNP1ucjs6.jpg" alt="Fantastic Four" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="clearfix"></span>

                                <div class="image-gallery">
                                    <h1><i class="mdi mdi-image"></i> Images</h1>
                                    <div class="section-content">
                                        <div id="lightgallery" class="gallery slick-initialized slick-slider"><button class="slick-prev slick-arrow" aria-label="Previous" type="button" style="">Previous</button>
                                            <div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 7540px; transform: translate3d(-780px, 0px, 0px);"><div class="image-item slick-slide slick-cloned" data-slick-index="-3" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="-2" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="-1" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//ognpnFJwztoLBZcIiqcBtAReXRe.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/ognpnFJwztoLBZcIiqcBtAReXRe.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" tabindex="0" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//ocUrMYbdjknu2TwzMHKT9PBBQRw.jpg">
                                                            <a href="" tabindex="0">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/ocUrMYbdjknu2TwzMHKT9PBBQRw.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-active" data-slick-index="1" aria-hidden="false" tabindex="0" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//lP5eKh8WOcPysfELrUpGhHJGZEH.jpg">
                                                            <a href="" tabindex="0">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/lP5eKh8WOcPysfELrUpGhHJGZEH.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-active" data-slick-index="2" aria-hidden="false" tabindex="0" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//cJJis9t8yFJj62lJCFl9jkgyv9f.jpg">
                                                            <a href="" tabindex="0">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/cJJis9t8yFJj62lJCFl9jkgyv9f.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="3" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//fq7MtJi8F47xSRymC5z92pjl3pF.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/fq7MtJi8F47xSRymC5z92pjl3pF.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="4" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//bVUCEPQgOWXPRzSUfC69btbXS2I.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/bVUCEPQgOWXPRzSUfC69btbXS2I.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="5" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//9OpmByatUpjM2Lu6FvAZCwPju8K.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/9OpmByatUpjM2Lu6FvAZCwPju8K.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="6" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//kNOEIbTLvs9NbMITHuLifHBmuq8.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/kNOEIbTLvs9NbMITHuLifHBmuq8.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="7" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//jYfExhTyKn5fgE0xV5XVd6LTHAF.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/jYfExhTyKn5fgE0xV5XVd6LTHAF.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="8" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//5GGb8iByVTF0gTov4eTQWRSSr1F.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/5GGb8iByVTF0gTov4eTQWRSSr1F.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="9" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//qTY3iK1w0FexvZPGE80MzKONL5S.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/qTY3iK1w0FexvZPGE80MzKONL5S.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="10" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="11" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide" data-slick-index="12" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//ognpnFJwztoLBZcIiqcBtAReXRe.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/ognpnFJwztoLBZcIiqcBtAReXRe.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="13" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//ocUrMYbdjknu2TwzMHKT9PBBQRw.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/ocUrMYbdjknu2TwzMHKT9PBBQRw.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="14" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//lP5eKh8WOcPysfELrUpGhHJGZEH.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/lP5eKh8WOcPysfELrUpGhHJGZEH.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="15" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//cJJis9t8yFJj62lJCFl9jkgyv9f.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/cJJis9t8yFJj62lJCFl9jkgyv9f.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="16" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//fq7MtJi8F47xSRymC5z92pjl3pF.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/fq7MtJi8F47xSRymC5z92pjl3pF.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="17" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//bVUCEPQgOWXPRzSUfC69btbXS2I.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/bVUCEPQgOWXPRzSUfC69btbXS2I.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="18" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//9OpmByatUpjM2Lu6FvAZCwPju8K.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/9OpmByatUpjM2Lu6FvAZCwPju8K.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="19" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//kNOEIbTLvs9NbMITHuLifHBmuq8.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/kNOEIbTLvs9NbMITHuLifHBmuq8.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="20" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//jYfExhTyKn5fgE0xV5XVd6LTHAF.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/jYfExhTyKn5fgE0xV5XVd6LTHAF.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="21" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//5GGb8iByVTF0gTov4eTQWRSSr1F.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/5GGb8iByVTF0gTov4eTQWRSSr1F.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="22" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//qTY3iK1w0FexvZPGE80MzKONL5S.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/qTY3iK1w0FexvZPGE80MzKONL5S.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="23" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/rxn2YIGnY1ftb9Rn6Sd2A0hZrh8.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="24" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/5ZVKyqUws4vMgCBq2EKLlX6m97p.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div><div class="image-item slick-slide slick-cloned" data-slick-index="25" aria-hidden="true" tabindex="-1" style="width: 230px;">
                                                        <p class="slide-item" data-src="https://image.tmdb.org/t/p/w1280//ognpnFJwztoLBZcIiqcBtAReXRe.jpg">
                                                            <a href="" tabindex="-1">
                                                                <img class="img-responsive" src="https://image.tmdb.org/t/p/w300/ognpnFJwztoLBZcIiqcBtAReXRe.jpg" alt="movie image">
                                                            </a>
                                                        </p>
                                                    </div></div></div>












                                            <button class="slick-next slick-arrow" aria-label="Next" type="button" style="">Next</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-5">
                            <br class="visible-xs">
                            <br class="visible-xs">
                            <div class="item-right-sidebar">
                                <div class="sidebar-ad">
                                    <img src="/images/rectangle-code.png" alt="ad" class="img-responsive">
                                </div>

                                <div class="sidebar-gen">
                                    <h1>Movie Info</h1>
                                </div>
                                <ul class="list-unstyled">
                                    <li>
                                        <strong><i class="mdi mdi-check-all"></i> Status:</strong>
                                        <span>Released</span>
                                    </li>
                                    <li>
                                        <strong><i class="mdi mdi-clock"></i> Runtime:</strong>
                                        <span>110m</span>
                                    </li>
                                    <li>
                                        <strong><i class="mdi mdi-chart-line"></i> Popularity:</strong>
                                        <span>363.548</span>
                                    </li>
                                    <li>
                                        <strong><i class="mdi mdi-account-switch"></i> Language:</strong>
                                        <span>en</span>
                                    </li>
                                </ul>
                                <span class="separator"></span>

                                <ul class="list-unstyled">
                                    <li>
                                        <strong><i class="mdi mdi-credit-card"></i> Budget:</strong>
                                        <span>$42,000,000</span>
                                    </li>
                                    <li>
                                        <strong><i class="mdi mdi-snowman"></i> Revenue:</strong>
                                        <span>$24,573,617</span>
                                    </li>
                                </ul>
                                <span class="separator"></span>

                                <ul class="list-unstyled">
                                    <li>
                                        <strong><i class="mdi mdi-fire"></i> Vote Average:</strong>
                                        <span>7</span>
                                    </li>
                                    <li>
                                        <strong><i class="mdi mdi-database-plus"></i> Vote Count:</strong>
                                        <span>395</span>
                                    </li>
                                </ul>
                                <span class="separator"></span>

                                <div class="sidebar-gen">
                                    <h1>Genres</h1>
                                    <p class="genres">
                                        <span>Action</span>
                                        <span>Science Fiction</span>
                                    </p>
                                </div>

                                <div class="sidebar-gen hidden-sm hidden-xs">
                                    <h1>Keywords</h1>
                                    <p class="genres">
                                        <span>superhero</span>
                                        <span>based on comic</span>
                                        <span>psychotronic</span>
                                        <span>shared universe</span>
                                        <span>valiant comics</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="movie-extras">
                        <br>
                        <br>
                        <br>
                        <br>
                        <hr>
                        <div class="cr-section">
                            <div class="cr-header">
                                <ul class="list-unstyled">
                                    <li class="active"> <a data-toggle="tab" href="#cast"><i class="mdi  mdi-presentation-play"></i> Star Cast</a></li>
                                    <li> <a data-toggle="tab" href="#reviews"><i class="mdi  mdi-comment"></i> Reviews </a></li>
                                </ul>
                            </div>

                            <div class="tab-content and no-cnt">
                                <div id="cast" class="tab-pane fade in active">
                                    <div class="cast_list">
                                        <div class="row flex">                                    <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/12835/vin-diesel">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/7rwSXluNWZAluYMOEWBxkPmckES.jpg" class="img-responsive" alt="Vin Diesel">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Vin Diesel</p>
                                                            <p class="text-muted">Ray Garrison / Bloodshot</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1222992/eiza-gonzlez">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/2EpyDXqw1oyJnKayu2XshczjiBN.jpg" class="img-responsive" alt="Eiza González">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Eiza González</p>
                                                            <p class="text-muted">KT</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/209326/sam-heughan">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/fNq4foH7KiZVHQz20Pdu0sNQd75.jpg" class="img-responsive" alt="Sam Heughan">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Sam Heughan</p>
                                                            <p class="text-muted">Jimmy Dalton</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/20286/toby-kebbell">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/wQU3uFk2TWjT2qfs2Z6rkdbWbjx.jpg" class="img-responsive" alt="Toby Kebbell">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Toby Kebbell</p>
                                                            <p class="text-muted">Martin Axe</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/66441/talulah-riley">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/gkRWhxAli7JaMPX2MFeObrHTJkX.jpg" class="img-responsive" alt="Talulah Riley">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Talulah Riley</p>
                                                            <p class="text-muted">Gina DeCarlo</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1181327/lamorne-morris">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/qYbRsbxKsKExq0M0A21kX4duD9F.jpg" class="img-responsive" alt="Lamorne Morris">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Lamorne Morris</p>
                                                            <p class="text-muted">Wilfred Wigens</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>                                                                                                                            <div class="row flex">                                    <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/529/guy-pearce">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/vTqk6Nh3WgqPubkS23eOlMAwmwa.jpg" class="img-responsive" alt="Guy Pearce">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Guy Pearce</p>
                                                            <p class="text-muted">Dr. Emil Harting</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/238164/jhannes-haukur-jhannesson">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/oqZftP0WS1rD5NFpR7vLp6JU52I.jpg" class="img-responsive" alt="Jóhannes Haukur Jóhannesson">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Jóhannes Haukur Jóhannesson</p>
                                                            <p class="text-muted">Nick Baris</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1472892/alex-hernandez">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/zxgVPvrxYoyAZw2RQSAluHEXzdZ.jpg" class="img-responsive" alt="Alex Hernandez">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Alex Hernandez</p>
                                                            <p class="text-muted">Tibbs</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1716711/siddharth-dhananjay">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/rz2fIkfO13cvoDSlki6BjoBxcLF.jpg" class="img-responsive" alt="Siddharth Dhananjay">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Siddharth Dhananjay</p>
                                                            <p class="text-muted">Eric</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1838215/tamer-burjaq">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/rz1FOttxvkeVkAh7XxjBwd8eGgC.jpg" class="img-responsive" alt="Tamer Burjaq">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Tamer Burjaq</p>
                                                            <p class="text-muted">Mombasa Gunman</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1541472/clyde-berning">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/pZIfJFgAYnj1dkopGC9ciTr0hR7.jpg" class="img-responsive" alt="Clyde Berning">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Clyde Berning</p>
                                                            <p class="text-muted">Mombasa Hostage</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>                                                                                                                            <div class="row flex">                                    <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1184628/david-dukas">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/5LvqM7MOLxklChoN9BQF0KxktTz.jpg" class="img-responsive" alt="David Dukas">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>David Dukas</p>
                                                            <p class="text-muted">Merc Driver</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1261817/tyrel-meyer">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/mowoOqDvHBbpvj85NBSwjx12gF5.jpg" class="img-responsive" alt="Tyrel Meyer">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Tyrel Meyer</p>
                                                            <p class="text-muted">Merc 2</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1654627/alex-anlos">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/oLMoKXYrd38zCmtLZOfcgKAT5EL.jpg" class="img-responsive" alt="Alex Anlos">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Alex Anlos</p>
                                                            <p class="text-muted">Baris Merc</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/2436897/maarten-rmer">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/gpnIbijkg1zWBoUMs7GicsV5IGm.jpg" class="img-responsive" alt="Maarten Römer">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Maarten Römer</p>
                                                            <p class="text-muted">Tech #2</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/207818/patrick-kerton">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/5kzVfgHiQhFOPZ5J4cwH5vF3Jzn.jpg" class="img-responsive" alt="Patrick Kerton">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Patrick Kerton</p>
                                                            <p class="text-muted">Truck Driver</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <div class="cast-card">
                                                    <a href="/person/1288410/ryan-kruger">
                                                        <div class="img-container">

                                                            <img src="https://image.tmdb.org/t/p/w300/728UvPgJoGSmF13tAbOdIfXshb1.jpg" class="img-responsive" alt="Ryan Kruger">
                                                        </div>
                                                        <div class="card-bt">
                                                            <p>Ryan Kruger</p>
                                                            <p class="text-muted">British Man</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>                                                                                                            </div>
                                </div>
                                <div id="reviews" class="tab-pane fade">
                                    <ul class="list-unstyled review-list">
                                    </ul>

                                    <div class="login-comment">
                                        please <a href="/login">Login</a> to add review
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>

                <div id="trailer-modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection