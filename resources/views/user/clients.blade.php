@extends('layout.default')

@section('title')
    <title>@lang('user.my-seedboxes') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_clients', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('user.my-seedboxes')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-2 col-sm-offset-1">
            <div class="well well-sm mt-0">
                <h3>@lang('user.add-seedbox')</h3>
                <form role="form" method="POST" action="{{ route('addcli', ['username' => $user->username, 'id' => $user->id]) }}">
                @csrf
                <div class="form-group input-group">
                    <input type="password" name="password" class="form-control"
                           placeholder="@lang('user.current-password')" required>
                </div>
                <div class="form-group input-group">
                    <input type="text" name="ip" class="form-control"
                           placeholder="@lang('user.client-ip-address')" required>
                </div>
                <div class="form-group input-group">
                    <input type="text" name="client_name" class="form-control"
                           placeholder="@lang('user.username-seedbox')" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm">@lang('common.submit')</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="well well-sm mt-0">
                <p class="lead text-orange text-center"><i class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i>
                    <strong>{{ strtoupper(trans('user.disclaimer')) }}</strong> <i
                            class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i></p>
                <p class="lead text-orange text-center">@lang('user.disclaimer-info')
                    &nbsp;<br><strong>@lang('user.disclaimer-info-bordered')</strong></p>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="container box">
            <h3 class="text-center">@lang('user.my-seedboxes')</h3>
            @if (count($clients) > 0)
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped table-hover">
                        <tr>
                            <th>@lang('torrent.agent')</th>
                            <th>IP</th>
                            <th>@lang('common.added')</th>
                            <th>@lang('common.remove')</th>
                        </tr>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->ip }}</td>
                                <td>{{ $client->created_at }}</td>
                                <td>
                                    <form role="form" method="POST" action="{{ route('rmcli', ['username' => $user->username, 'id' => $user->id]) }}">
                                    @csrf
                                    <input type='hidden' name="cliid" value="{{ $client->id }}">
                                    <input type="hidden" name="userid" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-danger">@lang('common.delete')</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <li class="list-group-item">
                    <h4 class="text-center">@lang('user.no-seedboxes')</h4>
                </li>
            @endif
        </div>
    </div>
@endsection
