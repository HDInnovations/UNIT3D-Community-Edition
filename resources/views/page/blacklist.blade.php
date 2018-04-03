@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('blacklist') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }} {{ trans('common.blacklist') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient red">
			<div class="inner_content">
				<div class="page-title"><h1>{{ config('other.title') }} {{ trans('common.blacklist') }}</h1></div>
			</div>
		</div>
        <div class="alert alert-info" id="alert1">
            <center>
                <span>
                    {{ trans('page.blacklist-desc', ['title' => config('other.title')]) }}
                </span>
            </center>
        </div>
    <div class="row black-list">
        <h2>{{ trans('page.blacklist-clients') }}</h2>
        @foreach($clients as $client)
        <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="text-center black-item">
                <h4>{{ $client }}</h4>
                <span>{{ trans('page.blacklist-btclient') }}</span>
                <i class="fa fa-ban text-red black-icon"></i>
            </div>
        </div>
        @endforeach
	</div>
    <br>
    <hr>
    <div class="row black-list">
        <h2>{{ trans('page.blacklist-browsers') }}</h2>
        @foreach($browsers as $browser)
        <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="text-center black-item">
                <h4>{{ $browser }}</h4>
                <span>{{ trans('page.blacklist-webbrowser') }}</span>
                <i class="fa fa-ban text-red black-icon"></i>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
