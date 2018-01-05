@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('blacklist') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ Config::get('other.title') }} {{ trans('common.blacklist') }}</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient red">
			<div class="inner_content">
				<div class="page-title"><h1>{{ Config::get('other.title') }} {{ trans('common.blacklist') }}</h1></div>
			</div>
		</div>
        <div class="alert alert-info" id="alert1">
            <center>
                <span>
                    The Following Browsers and Bittorrent Clients Are Blacklisted/Forbidden From Annoucing To {{ Config::get('other.title') }}
                </span>
            </center>
        </div>
    <div class="row black-list">
        <h2>Clients</h2>
        @foreach($clients as $client)
        <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="text-center black-item">
                <h4>{{ $client }}</h4>
                <span>Bittorrent Client</span>
                <i class="fa fa-ban text-red black-icon"></i>
            </div>
        </div>
        @endforeach
	</div>
    <br>
    <hr>
    <div class="row black-list">
        <h2>Browsers</h2>
        @foreach($browsers as $browser)
        <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="text-center black-item">
                <h4>{{ $browser }}</h4>
                <span>Web Browser</span>
                <i class="fa fa-ban text-red black-icon"></i>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@stop
