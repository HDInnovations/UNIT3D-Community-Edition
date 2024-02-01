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
        {{ __('common.users') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.statsusermenu')
@endsection

@section('page', 'page__stats--seedtime')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Most Upload Snatches</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('common.user') }}</th>
                        <th>Uploads Snatched</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <x-user_tag :user="$user" :anon="$user->private_profile" />
                            </td>
                            <td>{{ $user->upload_snatches_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
