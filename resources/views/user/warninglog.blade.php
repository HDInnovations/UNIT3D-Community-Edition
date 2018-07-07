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
    <div class="container">
        <div class="block">
            <h2><a class="view-user" data-id="{{ $user->id }}" data-slug="{{ $user->username }}"
                   href="{{ route('profile', ['username' =>  $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a>
                Warnings Log</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>Warnings {{ $warningcount }} </strong>
                        </span>
                        <div class="pull-right">
                            <a href="{{ route('massDeactivateWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-success" data-toggle="tooltip" title=""
                                        data-original-title="Deactivate All"><i
                                            class="fa fa-check"></i> Deactivate All</button>
                            </a>
                            <a href="{{ route('massDeleteWarnings', ['username' =>  $user->username, 'id' => $user->id]) }}">
                                <button type="button" class="btn btn btn-danger" data-toggle="tooltip" title=""
                                        data-original-title="Delete All"><i
                                            class="fa fa-times"></i> Delete All</button>
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
                        @if(count($warnings) == 0)
                            <tr>
                                <td>
                                    <p>The are no warnings in the database for this user!</p>
                                </td>
                            </tr>
                        @else
                            @foreach($warnings as $warning)
                                <tr>
                                    <td>
                                        <a data-id="{{ $warning->staffuser->id }}"
                                           data-slug="{{ $warning->staffuser->username }}"
                                           href="{{ route('profile', ['username' => $warning->staffuser->username, 'id' => $warning->staffuser->id]) }}">{{ $warning->staffuser->username }}</a>
                                    </td>
                                    <td>
                                        <a data-id="{{ $warning->torrenttitle->id }}"
                                           data-slug="{{ $warning->torrenttitle->name }}"
                                           href="{{ route('torrent', ['slug' =>$warning->torrenttitle->slug, 'id' => $warning->torrenttitle->id]) }}">{{ $warning->torrenttitle->name }}</a>
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
                                        @if($warning->active == 1)
                                            <span class='label label-success'>Yes</span>
                                        @else
                                            <span class='label label-danger'>Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($warning->active == 1)
                                            <a href="{{ route('deactivateWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-warning">
                                                <i class="fa fa-power-off"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('deactivateWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-warning" disabled>
                                                <i class="fa fa-power-off"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('deleteWarning', ['id' => $warning->id]) }}" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
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
@endsection
