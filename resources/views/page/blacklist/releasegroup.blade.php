@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('releasegroup_blacklist') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }} Releasegroup Blacklist</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="alert alert-info" id="alert1">
                <div class="text-center">
                    <span>
                        The Following Release Groups Are Blacklisted/Forbidden On {{ config('other.title') }}
                    </span>
                </div>
            </div>
            <div class="row black-list">
                <h2>Release Groups</h2>
                @foreach ($releasegroups as $releasegroup)
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="text-center black-item">
                            <h4>{{ $releasegroup->name }}</h4>
                            <span>
                                <small>Added {{ \Carbon\Carbon::parse($releasegroup->created_at)->format('Y-m-d')}}</small>
                            </span>
                            <i class="fal fa-ban text-red black-icon"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection