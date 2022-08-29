@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('common.blacklist') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="alert alert-info" id="alert1">
                <div class="text-center">
                    <span>
                        {{ __('page.blacklist-desc', ['title' => config('other.title')]) }}
                    </span>
                </div>
            </div>
            <div class="row black-list">
                <h2>{{ __('page.blacklist-clients') }}</h2>
                @foreach ($clients as $client)
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="text-center black-item">
                            <h4>{{ $client->name }}</h4>
                            <span>{{ __('page.blacklist-btclient') }}</span>
                            <i class="fal fa-ban text-red black-icon"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
