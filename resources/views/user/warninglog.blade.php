@extends('layout.default')

@section('title')
    <title>WarningLog - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('user.warning-log')</span>
        </a>
    </li>
@endsection

@section('content')
    {{--Warnings--}}
    <div class="container">
        <div class="block">
            <h2>
                <a href="{{ route('profile', ['username' =>  $user->username, 'id' => $user->id]) }}">
                    {{ $user->username }}
                </a>
                @lang('user.warning-log')
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>@lang('user.warnings') {{ $warningcount }} </strong>
                        </span>
                        <div class="pull-right">
                            <a href="{{ route('massDeactivateWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-success" data-toggle="tooltip"
                                        data-original-title="@lang('user.deactivate-all')"><i
                                            class="{{ config('other.font-awesome') }} fa-check"></i> @lang('user.deactivate-all')
                            </a>
                            <a href="{{ route('massDeleteWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-danger" data-toggle="tooltip"
                                        data-original-title="@lang('user.delete-all')"><i
                                            class="{{ config('other.font-awesome') }} fa-times"></i> @lang('user.delete-all')</button>
                            </a>
                        </div>
                    </h2>
                    <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>@lang('user.warned-by')</th>
                            <th>@lang('torrent.torrent')</th>
                            <th>@lang('common.reason')</th>
                            <th>@lang('user.created-on')</th>
                            <th>@lang('user.expires-on')</th>
                            <th>@lang('user.active')</th>
                            <th>@lang('user.deactivate')</th>
                            <th>@lang('common.delete')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($warnings) == 0)
                            <tr>
                                <td>
                                    <p>@lang('user.no-warning')</p>
                                </td>
                            </tr>
                        @else
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>
                                        <a href="{{ route('profile', ['username' => $warning->staffuser->username, 'id' => $warning->staffuser->id]) }}">
                                            {{ $warning->staffuser->username }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('torrent', ['slug' =>$warning->torrenttitle->slug, 'id' => $warning->torrenttitle->id]) }}">
                                            {{ $warning->torrenttitle->name }}
                                        </a>
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
                                            <span class='label label-success'>Yes</span>
                                        @else
                                            <span class='label label-danger'>Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($warning->active == 1)
                                            <a href="{{ route('deactivateWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-warning">
                                                <i class="{{ config('other.font-awesome') }} fa-power-off"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('deactivateWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-warning" disabled>
                                                <i class="{{ config('other.font-awesome') }} fa-power-off"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('deleteWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-danger">
                                            <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            {{ $warnings->links() }}
        </div>
    </div>

    {{--SoftDeleted Warnings --}}
    <div class="container">
        <div class="block">
            <h2>
                <span class="text-bold text-orange">
                    @lang('user.soft-deleted-warnings') {{ $softDeletedWarningCount }}
                </span>
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>@lang('user.warned-by')</th>
                                <th>@lang('torrent.torrent')</th>
                                <th>@lang('common.reason')</th>
                                <th>@lang('user.created-on')</th>
                                <th>@lang('user.deleted-on')</th>
                                <th>@lang('user.deleted-by')</th>
                                <th>@lang('user.restore')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($softDeletedWarnings) == 0)
                                <tr>
                                    <td>
                                        <p>@lang('user.no-soft-warning')</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($softDeletedWarnings as $softDeletedWarning)
                                    <tr>
                                        <td>
                                            <a href="{{ route('profile', ['username' => $softDeletedWarning->staffuser->username, 'id' => $softDeletedWarning->staffuser->id]) }}">
                                                {{ $softDeletedWarning->staffuser->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('torrent', ['slug' =>$softDeletedWarning->torrenttitle->slug, 'id' => $softDeletedWarning->torrenttitle->id]) }}">
                                                {{ $softDeletedWarning->torrenttitle->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $softDeletedWarning->reason }}
                                        </td>
                                        <td>
                                            {{ $softDeletedWarning->created_at }}
                                        </td>
                                        <td>
                                            {{ $softDeletedWarning->deleted_at }}
                                        </td>
                                        <td>
                                            <a href="{{ route('profile', ['username' => $softDeletedWarning->deletedBy->username, 'id' => $softDeletedWarning->deletedBy->id]) }}">
                                                {{ $softDeletedWarning->deletedBy->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('restoreWarning', ['id' => $softDeletedWarning->id]) }}" class="btn btn-xs btn-info">
                                                <i class="{{ config('other.font-awesome') }} fa-sync-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ $softDeletedWarnings->links() }}
        </div>
    </div>
@endsection
