@extends('layout.default')

@section('title')
    <title>@lang('common.about') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.about')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('about') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.about')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header aboutus">
                <div class="aboutus_content">
                    <div class="content">
                        <h2>@lang('page.aboutus-header')</h2>
                        <img src="{{ url('img/deadpool.png') }}" alt="deadpool" width="902" height="298">

                        <h3>@lang('page.aboutus-welcome') {{ config('other.title') }}</h3>
                        <p>{!! trans('page.aboutus-welcome-desc', ['title' => config('other.title')]) !!}</p>

                        <h4><i class="{{ config('other.font-awesome') }} fa-globe"
                               aria-hidden="true"></i> @lang('page.aboutus-advantage') {{ config('other.title') }}
                            <i class="{{ config('other.font-awesome') }} fa-globe" aria-hidden="true"></i></h4>
                        <div class="wrapper">
                            <div>
                                <div>1</div>
                                <p>@lang('page.aboutus-advantage1')</p>
                            </div>

                            <div>
                                <div>2</div>
                                <p>{!! trans('page.aboutus-advantage2') !!}</p>
                            </div>

                            <div>
                                <div>3</div>
                                <p>@lang('page.aboutus-advantage3')</p>
                            </div>

                            <div>
                                <div>4</div>
                                <p>@lang('page.aboutus-advantage4', ['title' => config('other.title')])</p>
                            </div>

                            <div>
                                <div>5</div>
                                <p>@lang('page.aboutus-advantage5')</p>
                            </div>


                            <h4><i class="{{ config('other.font-awesome') }} fa-globe" aria-hidden="true"></i> @lang('page.aboutus-rules') <i
                                        class="{{ config('other.font-awesome') }} fa-globe" aria-hidden="true"></i></h4>
                            <div>
                                <div>1</div>
                                <p>@lang('page.aboutus-rules1')</p>
                            </div>

                            <div>
                                <div>2</div>
                                <p>@lang('page.aboutus-rules2')</p>
                            </div>

                            <div>
                                <div>3</div>
                                <p>@lang('page.aboutus-rules3', ['title' => config('other.title')])</p>
                            </div>
                        </div>

                        <a href="{{ route('contact.index') }}"
                           class="contact button white">@lang('common.contact') {{ config('other.title') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
