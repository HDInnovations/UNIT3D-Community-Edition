@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Clients
    </li>
@endsection

@section('page', 'page__stats--clients')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Clients</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>{{ __('common.users') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client => $count)
                        <tr>
                            <td>{{ $client }}</td>
                            <td>Used by {{ $count }} users(s)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
