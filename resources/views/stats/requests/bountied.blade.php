@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.stats') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('bountied') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.top-bountied') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statsrequestmenu')

        <div class="block">
            <h2>{{ __('stat.top-bountied') }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-success"><strong><i class="{{ config('other.font-awesome') }} fa-trophy"></i>
                            {{ __('stat.top-bountied') }}
                        </strong></p>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('request.request') }}</th>
                            <th>{{ __('request.bounty') }}</th>
                            <th>{{ __('request.filled') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bountied as $key => $b)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>
                                    <a class="text-bold" href="{{ route('request', ['id' => $b->id]) }}">
                                        {{ $b->name }}
                                    </a>
                                </td>
                                <td><span class="text-green">{{ $b->bounty }}</span></td>
                                <td>
                                    @if ($b->filled_hash == null)
                                        <span class="label label-default" data-toggle="tooltip"
                                              data-original-title="{{ __('stat.request-not-fulfilled') }}">
                                                <i class="{{ config('other.font-awesome') }} fa-times-circle text-danger"></i>
                                            </span>
                                    @elseif ($b->filled_hash != null && $b->approved_by == null)
                                        <span class="label label-default" data-toggle="tooltip"
                                              data-original-title="{{ __('stat.request-pending-aproval') }}">
                                                <i class="{{ config('other.font-awesome') }} fa-question-circle text-info"></i>
                                            </span>
                                    @else
                                        <span class="label label-default" data-toggle="tooltip"
                                              data-original-title="{{ __('stat.request-fulfilled') }}">
                                                <i class="{{ config('other.font-awesome') }} fa-check-circle text-success"></i>
                                            </span>
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
