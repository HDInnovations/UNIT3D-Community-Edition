@extends('layout.default')

@section('title')
    <title>Warnings Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Warnings Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.warnings.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.warnings-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('staff.warnings-log') }}</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-blue"> {{ __('common.warnings') }}
                            <strong><i class="{{ config('other.font-awesome') }} fa-note"></i> {{ $warningcount }} </strong>
                        </span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('common.user') }}</th>
                                <th>{{ __('user.warned-by') }}</th>
                                <th>{{ __('torrent.torrent') }}</th>
                                <th>{{ __('common.reason') }}</th>
                                <th>{{ __('user.created-on') }}</th>
                                <th>{{ __('user.expires-on') }}</th>
                                <th>{{ __('common.active') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($warnings) == 0)
                                <p>The are no warnings in the database!</p>
                            @else
                                @foreach ($warnings as $warning)
                                    <tr>
                                        <td>
                                            <a class="text-bold"
                                               href="{{ route('users.show', ['username' => $warning->warneduser->username]) }}">
                                                {{ $warning->warneduser->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-bold"
                                               href="{{ route('users.show', ['username' => $warning->staffuser->username]) }}">
                                                {{ $warning->staffuser->username }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(isset($warning->torrent))
                                                <a class="text-bold"
                                                   href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                                    {{ $warning->torrenttitle->name }}
                                                </a>
                                            @else
                                                n/a
                                            @endif
                                        </td>
                                        <td>
                                            {{ $warning->reason }}
                                        </td>
                                        <td>
                                            {{ $warning->created_at }}
                                        </td>
                                        <td>
                                            {{ $warning->expires_on }}
                                        </td>
                                        <td>
                                            @if ($warning->active == 1)
                                                <span class='label label-success'>{{ __('common.yes') }}</span>
                                            @else
                                                <span class='label label-danger'>{{ __('common.expired') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $warnings->links() }}
            </div>
        </div>
    </div>
@endsection
