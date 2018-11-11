@extends('layout.default')

@section('title')
    <title>WarningLog - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">WarningLog</span>
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
                Warnings Log
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>Warnings {{ $warningcount }} </strong>
                        </span>
                        <div class="pull-right">
                            <a href="{{ route('massDeactivateWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-success" data-toggle="tooltip"
                                        data-original-title="Deactivate All"><i
                                            class="{{ config('other.font-awesome') }} fa-check"></i> Deactivate All</button>
                            </a>
                            <a href="{{ route('massDeleteWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-danger" data-toggle="tooltip"
                                        data-original-title="Delete All"><i
                                            class="{{ config('other.font-awesome') }} fa-times"></i> Delete All</button>
                            </a>
                        </div>
                    </h2>
                    <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Warned By</th>
                            <th>Torrent</th>
                            <th>Reason</th>
                            <th>Created On</th>
                            <th>Expires On</th>
                            <th>Active</th>
                            <th>Deactivate</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($warnings) == 0)
                            <tr>
                                <td>
                                    <p>The are no warnings in the database for this user!</p>
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
                    Soft Deleted Warnings {{ $softDeletedWarningCount }}
                </span>
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Warned By</th>
                                <th>Torrent</th>
                                <th>Reason</th>
                                <th>Created On</th>
                                <th>Deleted On</th>
                                <th>Deleted By</th>
                                <th>Restore</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($softDeletedWarnings) == 0)
                                <tr>
                                    <td>
                                        <p>The are soft deleted warnings in the database for this user!</p>
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
