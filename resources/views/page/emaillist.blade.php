@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('emaillist') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @if (config('email-white-blacklist.enabled') == 'allow')
                    {{ config('other.title') }} {{ trans('common.email-whitelist') }}
                @endif
                @if (config('email-white-blacklist.enabled') == 'block')
                    {{ config('other.title') }} {{ trans('common.email-blacklist') }}
                @endif
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">

            @if (config('email-white-blacklist.enabled') == null)
                <div class="alert alert-info" id="alert1">
                    <div class="text-center">
                        <span>
                            {{ config('other.title') }} {{ trans('common.email-list-notactive') }}
                        </span>
                    </div>
                </div>
            @else

            @if (config('email-white-blacklist.enabled') == 'allow')
                <div class="header gradient green">
                    <div class="inner_content">
                        <div class="page-title"><h1>{{ config('other.title') }} {{ trans('common.email-whitelist') }}</h1></div>
                    </div>
                </div>
            @endif
            @if (config('email-white-blacklist.enabled') == 'block')
                <div class="header gradient red">
                    <div class="inner_content">
                        <div class="page-title"><h1>{{ config('other.title') }} {{ trans('common.email-blacklist') }}</h1></div>
                    </div>
                </div>
            @endif

            <div class="alert alert-info" id="alert1">
                <div class="text-center">
                    @if (config('email-white-blacklist.enabled') == 'allow')
                        <span>
                            {{ trans('page.email-whitelist-desc', ['title' => config('other.title')]) }}
                        </span>
                    @endif
                    @if (config('email-white-blacklist.enabled') == 'block')
                        <span>
                            {{ trans('page.email-blacklist-desc', ['title' => config('other.title')]) }}
                        </span>
                    @endif
                </div>
            </div>
            @endif

            @if (config('email-white-blacklist.enabled') == 'allow')
            <div class="row black-list">
                @foreach($whitelist as $w)
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="text-center black-item">
                            <span class="text-bold">{{ $w }}</span>
                            <h4>{{ trans('page.whitelist-emaildomain') }}</h4>
                            <i class="fa fa-check text-green black-icon"></i>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            @if (config('email-white-blacklist.enabled') == 'block')
            <div class="row black-list">
                @foreach($blacklist as $b)
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="text-center black-item">
                            <span class="text-bold">{{ $b }}</span>
                            <h4>{{ trans('page.blacklist-emaildomain') }}</h4>
                            <i class="fa fa-ban text-red black-icon"></i>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
@endsection
