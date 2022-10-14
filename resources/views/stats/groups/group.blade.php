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
    <li class="breadcrumbV2">
        <a href="{{ route('groups') }}" class="breadcrumb__link">
            {{ __('stat.groups') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('stat.group') }}
    </li>
@endsection

@section('page', 'page__stats--group')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $group->name }} {{ __('stat.group') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('stat.registration-date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <x-user_tag :user="$user" :anon="$user->private_profile" />
                            </td>
                            <td>
                                <time datetime="{{ $user->created_at }}" title="{{ $user->created_at }}">
                                    {{ date('d M Y', strtotime($user->created_at)) }}
                                </time>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links('partials.pagination') }}
    </section>
@endsection
