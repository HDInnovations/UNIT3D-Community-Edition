@extends('layout.default')

@section('title')
    <title>{{ trans('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.stats') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('bountied') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-bountied') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statsrequestmenu')

        <div class="block">
            <h2>{{ trans('stat.top-bountied') }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-success"><strong><i class="fa fa-trophy"></i> {{ trans('stat.top-bountied') }}
                        </strong></p>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('request.request') }}</th>
                            <th>{{ trans('request.bounty') }}</th>
                            <th>{{ trans('request.filled') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bountied as $key => $b)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>
                                    <a class="view-torrent" data-id="{{ $b->id }}"
                                       href="{{ route('request', ['id' => $b->id]) }}" data-toggle="tooltip"
                                       title="" data-original-title="{{ $b->name }}">{{ $b->name }}</a>
                                </td>
                                <td><span class="text-green">{{ $b->bounty }}</span></td>
                                <td>
                                    @if($b->filled_hash == null)
                                        <button class="btn btn-xxs" data-toggle="tooltip" title=""
                                                data-original-title="{{ trans('stat.request-not-fulfilled') }}">
                                            <i class="fa fa-times-circle text-danger"></i></button>
                                    @elseif($b->filled_hash != null && $b->approved_by == null)
                                        <button class="btn btn-xxs" data-toggle="tooltip" title=""
                                                data-original-title="{{ trans('stat.request-pending-aproval') }}">
                                            <i class="fa fa-question-circle text-info"></i></button>
                                    @else
                                        <button class="btn btn-xxs" data-toggle="tooltip" title=""
                                                data-original-title="{{ trans('stat.request-fulfilled') }}">
                                            <i class="fa fa-check-circle text-success"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
