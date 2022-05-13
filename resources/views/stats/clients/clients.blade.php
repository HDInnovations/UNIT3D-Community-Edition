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
        <a href="{{ route('languages') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Clients</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>Clients</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Client</th>
                            <th class="text-right">{{ __('common.users') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client => $count)
                            <tr>
                                <td class="text-success">
                                    {{ $client }}
                                </td>
                                <td>
                                    <p class="text-blue text-right">
                                        Used by {{ $count }} users(s)
                                    </p>
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
