@extends('layout.default')

@section('title')
    <title>WarningLog - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('user.warning-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>
                <a href="{{ route('users.show', ['username' => $user->username]) }}">
                    {{ $user->username }}
                </a>
                {{ __('user.warning-log') }}
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>{{ __('user.warnings') }} {{ $warningcount }} </strong>
                        </span>
                        <div class="pull-right">
                            <form role="form" method="POST"
                                  action="{{ route('massDeactivateWarnings', ['username' => $user->username]) }}"
                                  style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-warning">
                                    <i class="{{ config('other.font-awesome') }} fa-power-off"></i> {{ __('user.deactivate-all') }}
                                </button>
                            </form>
                            <form role="form"
                                  action="{{ route('massDeleteWarnings', ['username' => $user->username]) }}"
                                  method="POST"
                                  style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">
                                    <i class="{{ config('other.font-awesome') }} fa-trash"></i> {{ __('user.delete-all') }}
                                </button>
                            </form>
                        </div>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('user.warned-by') }}</th>
                                <th>{{ __('torrent.torrent') }}</th>
                                <th>{{ __('common.reason') }}</th>
                                <th>{{ __('user.created-on') }}</th>
                                <th>{{ __('user.expires-on') }}</th>
                                <th>{{ __('user.active') }}</th>
                                <th>{{ __('user.deactivate') }}</th>
                                <th>{{ __('common.delete') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($warnings) == 0)
                                <tr>
                                    <td>
                                        <p>{{ __('user.no-warning') }}</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($warnings as $warning)
                                    <tr>
                                        <td>
                                            <a href="{{ route('users.show', ['username' => $warning->staffuser->username]) }}">
                                                {{ $warning->staffuser->username }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(isset($warning->torrent))
                                                <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
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
                                                <span class='label label-danger'>{{ __('user.expired') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form role="form" method="POST"
                                                  action="{{ route('deactivateWarning', ['id' => $warning->id]) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-xs btn-warning  @if ($warning->active == 0) disabled @endif">
                                                    <i class="{{ config('other.font-awesome') }} fa-power-off"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('deleteWarning', ['id' => $warning->id]) }}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
                                            </form>
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

    <div class="container">
        <div class="block">
            <h2>
                <span class="text-bold text-orange">
                    {{ __('user.soft-deleted-warnings') }} {{ $softDeletedWarningCount }}
                </span>
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('user.warned-by') }}</th>
                                <th>{{ __('torrent.torrent') }}</th>
                                <th>{{ __('common.reason') }}</th>
                                <th>{{ __('user.created-on') }}</th>
                                <th>{{ __('user.deleted-on') }}</th>
                                <th>{{ __('user.deleted-by') }}</th>
                                <th>{{ __('user.restore') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($softDeletedWarnings) == 0)
                                <tr>
                                    <td>
                                        <p>{{ __('user.no-soft-warning') }}</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($softDeletedWarnings as $softDeletedWarning)
                                    <tr>
                                        <td>
                                            <a
                                                    href="{{ route('users.show', ['username' => $softDeletedWarning->staffuser->username]) }}">
                                                {{ $softDeletedWarning->staffuser->username }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(isset($softDeletedWarning->torrent))
                                                <a href="{{ route('torrent', ['id' => $softDeletedWarning->torrenttitle->id]) }}">
                                                    {{ $softDeletedWarning->torrenttitle->name }}
                                                </a>
                                            @else
                                                n/a
                                            @endif
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
                                            <a
                                                    href="{{ route('users.show', ['username' => $softDeletedWarning->deletedBy->username]) }}">
                                                {{ $softDeletedWarning->deletedBy->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <form role="form" method="POST"
                                                  action="{{ route('restoreWarning', ['id' => $softDeletedWarning->id]) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-xs btn-info  @if ($softDeletedWarning->active == 0) disabled @endif">
                                                    <i class="{{ config('other.font-awesome') }} fa-trash-restore"></i>
                                                </button>
                                            </form>
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
