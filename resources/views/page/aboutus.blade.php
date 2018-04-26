@extends('layout.default')

@section('title')
    <title>{{ trans('common.about') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ trans('common.about') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('about') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.about') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header aboutus">
                <div class="aboutus_content">
                    <div class="content">
                        <h2>{{ trans('page.aboutus-header') }}</h2>
                        <img src="{{ url('img/deadpool.png') }}" width="902" height="298">

                        <h3>{{ trans('page.aboutus-welcome') }} {{ config('other.title') }}</h3>
                        <p>{!! trans('page.aboutus-welcome-desc', ['title' => config('other.title')]) !!}</p>

                        <h4><i class="fa fa-globe"
                               aria-hidden="true"></i> {{ trans('page.aboutus-advantage') }} {{ config('other.title') }}
                            <i class="fa fa-globe" aria-hidden="true"></i></h4>
                        <div class="wrapper">
                            <div>
                                <div>1</div>
                                <p>{{ trans('page.aboutus-advantage1') }}</p>
                            </div>

                            <div>
                                <div>2</div>
                                <p>{!! trans('page.aboutus-advantage2') !!}</p>
                            </div>

                            <div>
                                <div>3</div>
                                <p>{{ trans('page.aboutus-advantage3') }}</p>
                            </div>

                            <div>
                                <div>4</div>
                                <p>{{ trans('page.aboutus-advantage4', ['title' => config('other.title')]) }}</p>
                            </div>

                            <div>
                                <div>5</div>
                                <p>{{ trans('page.aboutus-advantage5') }}</p>
                            </div>


                            <h4><i class="fa fa-globe" aria-hidden="true"></i> {{ trans('page.aboutus-rules') }} <i
                                        class="fa fa-globe" aria-hidden="true"></i></h4>
                            <div>
                                <div>1</div>
                                <p>{{ trans('page.aboutus-rules1') }}</p>
                            </div>

                            <div>
                                <div>2</div>
                                <p>{{ trans('page.aboutus-rules2') }}</p>
                            </div>

                            <div>
                                <div>3</div>
                                <p>{{ trans('page.aboutus-rules3', ['title' => config('other.title')]) }}</p>
                            </div>
                        </div>

                        <a href="{{ route('contact') }}"
                           class="contact button white">{{ trans('common.contact') }} {{ config('other.title') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
